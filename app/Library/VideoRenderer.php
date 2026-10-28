<?php

namespace App\Library;

use App\Models\UserVideo;
use RuntimeException;

/**
 * Renders a UserVideo by overlaying its text onto its template's
 * background video with FFmpeg. Output is always 1080x1920 (vertical,
 * matching Reels/Shorts/Status format).
 *
 * Text is NOT drawn with FFmpeg's drawtext filter -- live testing (on
 * this exact server's FFmpeg build, not just a local one) confirmed
 * drawtext's Bengali shaping is broken for common, everyday words (e.g.
 * "বেলা" rendering as "বলো", vowel signs like "ো" showing a dotted-circle
 * placeholder). Instead, the text is built as an SVG, which FFmpeg
 * rasterizes through its own bundled librsvg decoder -- that path renders
 * through Pango internally and shapes Bengali and Arabic correctly (also
 * confirmed empirically).
 *
 * The font is referenced by family name and resolved through a
 * request-scoped fontconfig config (see ensureFontconfig()), NOT embedded
 * in the SVG as a base64 `@font-face`. The embedded-font approach worked
 * in local testing but produced tofu boxes (empty glyph placeholders) in
 * production -- the librsvg build bundled with the production FFmpeg
 * doesn't honor `@font-face` the way the local one did. Fontconfig-based
 * family lookup is what every librsvg/Pango build already relies on for
 * ordinary system fonts, so it isn't sensitive to that kind of version
 * difference.
 *
 * The rasterized text is composited onto the background video with the
 * `overlay` filter.
 *
 * Font selection: the text's own script decides the font, not a setting
 * someone has to remember to pick -- Arabic text (Quran verses etc.) uses
 * Noto Naskh Arabic; everything else (Bengali or Latin/English) uses Noto
 * Sans Bengali, which has both scripts' glyphs merged into one file so it
 * never needs picking either.
 */
class VideoRenderer
{
    protected const CANVAS_WIDTH = 1080;
    protected const CANVAS_HEIGHT = 1920;

    protected function ffmpegBinary(): string
    {
        foreach (['/usr/bin/ffmpeg', '/usr/local/bin/ffmpeg'] as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        $which = trim((string) shell_exec('which ffmpeg 2>/dev/null'));
        return $which ?: 'ffmpeg';
    }

    protected function isArabic(string $text): bool
    {
        return (bool) preg_match('/\p{Arabic}/u', $text);
    }

    protected function fontPathForText(string $text): string
    {
        $dir = resource_path('fonts/video-maker');
        return $this->isArabic($text)
            ? $dir . '/NotoNaskhArabic-Regular.ttf'
            : $dir . '/NotoSansBengali-Regular.ttf';
    }

    /**
     * Font family name as embedded in the TTF's own name table (confirmed
     * via fc-query) -- this is what the SVG's font-family has to match for
     * fontconfig to resolve it to our bundled file.
     */
    protected function fontFamilyForText(string $text): string
    {
        return $this->isArabic($text) ? 'Noto Naskh Arabic' : 'Noto Sans Bengali';
    }

    /**
     * Writes (or reuses) a minimal fontconfig config that points only at
     * our bundled fonts directory, and returns its path so it can be fed
     * to FFmpeg via the FONTCONFIG_FILE env var. Using FONTCONFIG_FILE
     * (not FONTCONFIG_PATH) replaces the system config entirely for that
     * one process, which is fine here -- the SVG only ever asks for our
     * two font families, never a system one.
     */
    protected function ensureFontconfig(): string
    {
        $cacheDir = storage_path('app/fontconfig-cache');
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $confPath = storage_path('app/video-maker-fonts.conf');
        $fontDir = resource_path('fonts/video-maker');

        $xml = <<<CONF
<?xml version="1.0"?>
<!DOCTYPE fontconfig SYSTEM "fonts.dtd">
<fontconfig>
  <dir>{$fontDir}</dir>
  <cachedir>{$cacheDir}</cachedir>
</fontconfig>
CONF;

        // Regenerated on every call (it's tiny) so a change in paths
        // across deploys never leaves a stale file behind.
        file_put_contents($confPath, $xml);

        return $confPath;
    }

    /**
     * Rough character-count-based line wrapping -- good enough for short
     * quote/verse-style text. mb_* throughout so Bengali/Arabic multi-byte
     * characters are never split mid-character.
     *
     * @return string[]
     */
    protected function wrapLines(string $text, int $maxCharsPerLine = 22): array
    {
        $words = preg_split('/\s+/u', trim($text));
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $candidate = $current === '' ? $word : $current . ' ' . $word;
            if (mb_strlen($candidate) > $maxCharsPerLine && $current !== '') {
                $lines[] = $current;
                $current = $word;
            } else {
                $current = $candidate;
            }
        }
        if ($current !== '') {
            $lines[] = $current;
        }

        return $lines;
    }

    /**
     * Builds a full-canvas transparent SVG: a centered, rounded
     * semi-transparent box behind the text, with each wrapped line drawn
     * as its own <text> element (SVG doesn't reliably center multi-line
     * text any other way across renderers).
     *
     * Long text (the form allows up to 400 characters) is auto-shrunk so
     * the box never swallows most of the canvas and hides the background
     * video entirely -- line count is fixed by wrapLines() regardless of
     * font size, so shrinking the font directly shrinks the box height.
     */
    protected function buildSvg(string $text, string $color, int $fontSize, bool $rtl): string
    {
        $fontFamily = $this->fontFamilyForText($text);
        $lines = $this->wrapLines($text, $rtl ? 18 : 22);

        $boxPaddingY = 48;
        $boxWidth = self::CANVAS_WIDTH - 120;
        $maxBoxHeight = (int) round(self::CANVAS_HEIGHT * 0.8);
        $minFontSize = 22;

        do {
            $lineHeight = (int) round($fontSize * 1.45);
            $textBlockHeight = count($lines) * $lineHeight;
            $boxHeight = $textBlockHeight + ($boxPaddingY * 2);
            if ($boxHeight <= $maxBoxHeight || $fontSize <= $minFontSize) {
                break;
            }
            $fontSize -= 2;
        } while (true);
        $boxHeight = min($boxHeight, $maxBoxHeight);

        $boxX = (self::CANVAS_WIDTH - $boxWidth) / 2;
        $boxY = (self::CANVAS_HEIGHT - $boxHeight) / 2;

        $firstLineY = $boxY + $boxPaddingY + (int) round($fontSize * 0.85);
        $anchor = $rtl ? 'end' : 'middle';
        $textX = $rtl ? (self::CANVAS_WIDTH / 2 + ($boxWidth / 2) - 20) : self::CANVAS_WIDTH / 2;

        $textElements = '';
        foreach ($lines as $i => $line) {
            $safeLine = htmlspecialchars($line, ENT_XML1 | ENT_COMPAT, 'UTF-8');
            $y = $firstLineY + ($i * $lineHeight);
            $textElements .= sprintf(
                '<text x="%s" y="%s" font-size="%d" text-anchor="%s" fill="%s">%s</text>' . "\n",
                $textX,
                $y,
                $fontSize,
                $anchor,
                $color,
                $safeLine
            );
        }

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$this->canvasWidth()}" height="{$this->canvasHeight()}">
<defs>
<style>
text { font-family: '{$fontFamily}'; }
</style>
</defs>
<rect x="{$boxX}" y="{$boxY}" width="{$boxWidth}" height="{$boxHeight}" rx="24" fill="black" fill-opacity="0.45"/>
{$textElements}
</svg>
SVG;
    }

    protected function canvasWidth(): int
    {
        return self::CANVAS_WIDTH;
    }

    protected function canvasHeight(): int
    {
        return self::CANVAS_HEIGHT;
    }

    public function render(UserVideo $video): string
    {
        $template = $video->template;
        if (!$template) {
            throw new RuntimeException('Template not found.');
        }

        $backgroundPath = base_path($template->background_path);
        if (!file_exists($backgroundPath)) {
            throw new RuntimeException('Template background file missing on disk: ' . $template->background_path);
        }

        $ffmpeg = $this->ffmpegBinary();
        if (trim((string) shell_exec('which ffmpeg 2>/dev/null')) === '' && !file_exists($ffmpeg)) {
            throw new RuntimeException('FFmpeg is not available on this server.');
        }

        $font = $this->fontPathForText($video->text_content);
        if (!file_exists($font)) {
            throw new RuntimeException('Font file missing: ' . $font);
        }

        $outDir = base_path('uploads/user-videos');
        if (!is_dir($outDir)) {
            mkdir($outDir, 0755, true);
        }

        $rtl = $this->isArabic($video->text_content);
        $svg = $this->buildSvg(
            $video->text_content,
            $template->text_color ?: '#ffffff',
            (int) ($template->font_size ?: 60),
            $rtl
        );

        $svgFile = tempnam(sys_get_temp_dir(), 'vt_') . '.svg';
        file_put_contents($svgFile, $svg);

        $outputFilename = 'video_' . $video->id . '_' . bin2hex(random_bytes(6)) . '.mp4';
        $outputFullPath = $outDir . '/' . $outputFilename;

        // fps=25 and setsar=1 are defensive: an admin-uploaded background
        // can carry an odd/variable frame rate or non-square pixel aspect
        // ratio from however it was originally recorded/exported, and
        // libx264 can refuse to open the encoder at all over exactly that
        // ("Error while opening encoder - maybe incorrect parameters such
        // as bit_rate, rate, width or height") -- normalizing both before
        // the encoder ever sees them removes that as a failure mode
        // regardless of what the uploaded file's own metadata says.
        $filterComplex = sprintf(
            '[0:v]scale=%1$d:%2$d:force_original_aspect_ratio=increase,crop=%1$d:%2$d,fps=25,setsar=1[bg];[1:v]format=rgba[txt];[bg][txt]overlay=0:0,format=yuv420p',
            self::CANVAS_WIDTH,
            self::CANVAS_HEIGHT
        );

        // -threads 1 / x264-params threads=1 is required on this shared
        // host: libx264 defaults to one encoder thread per detected CPU
        // core, but the hosting account's LVE only allows a handful of
        // threads/processes -- on the real (many-core) physical host that
        // mismatch makes libx264 fail to even open the encoder ("Error
        // while opening encoder") before a single frame is processed,
        // regardless of the input file's own format (confirmed: this also
        // happened on an input that already matched the normalized
        // baseline exactly). Forcing a single thread keeps it within the
        // account's limits.
        $fontconfigFile = $this->ensureFontconfig();

        // FONTCONFIG_FILE points librsvg's Pango text layout at our
        // bundled Bengali/Arabic fonts by family name, instead of relying
        // on the SVG's own @font-face (see the class docblock for why
        // that broke in production).
        $cmd = sprintf(
            'FONTCONFIG_FILE=%s %s -y -stream_loop -1 -i %s -i %s -t %d -filter_complex %s -an -r 25 -c:v libx264 -preset fast -crf 23 -pix_fmt yuv420p -threads 1 -x264-params threads=1 -movflags +faststart %s 2>&1',
            escapeshellarg($fontconfigFile),
            escapeshellcmd($ffmpeg),
            escapeshellarg($backgroundPath),
            escapeshellarg($svgFile),
            (int) ($template->duration_seconds ?: 10),
            escapeshellarg($filterComplex),
            escapeshellarg($outputFullPath)
        );

        exec($cmd, $outputLines, $exitCode);
        @unlink($svgFile);

        if ($exitCode !== 0 || !file_exists($outputFullPath) || filesize($outputFullPath) < 1000) {
            $tail = implode("\n", array_slice($outputLines, -40));
            throw new RuntimeException('FFmpeg render failed (exit ' . $exitCode . '): ' . $tail);
        }

        return 'uploads/user-videos/' . $outputFilename;
    }

    /**
     * Renders just the text overlay (buildSvg + fontconfig, same code
     * path as render()) to a standalone PNG for diagnostics -- lets a
     * one-off route confirm the real SVG/fontconfig pipeline works on a
     * given server without spending a user's balance on a full video.
     */
    public function renderTextPreview(string $text, string $color, int $fontSize, string $outputPath): array
    {
        $ffmpeg = $this->ffmpegBinary();
        $rtl = $this->isArabic($text);
        $svg = $this->buildSvg($text, $color, $fontSize, $rtl);

        $svgFile = tempnam(sys_get_temp_dir(), 'vtprev_') . '.svg';
        file_put_contents($svgFile, $svg);

        $fontconfigFile = $this->ensureFontconfig();

        // -threads 1: the PNG encoder frame-threads too, and hits the same
        // shared-host LVE thread-limit failure as libx264 does in render()
        // ("ff_frame_thread_encoder_init failed" / "Resource temporarily
        // unavailable" -- a pthread_create failure, confirming that
        // diagnosis directly).
        $cmd = sprintf(
            'FONTCONFIG_FILE=%s %s -y -f lavfi -i %s -i %s -filter_complex %s -frames:v 1 -update 1 -threads 1 %s 2>&1',
            escapeshellarg($fontconfigFile),
            escapeshellcmd($ffmpeg),
            escapeshellarg(sprintf('color=c=0x123456:s=%dx%d', self::CANVAS_WIDTH, self::CANVAS_HEIGHT)),
            escapeshellarg($svgFile),
            escapeshellarg('[1:v]format=rgba[txt];[0:v][txt]overlay=0:0'),
            escapeshellarg($outputPath)
        );

        exec($cmd, $outputLines, $exitCode);
        @unlink($svgFile);

        return [
            'success' => $exitCode === 0 && file_exists($outputPath),
            'exit_code' => $exitCode,
            'ffmpeg_output' => implode("\n", array_slice($outputLines, -30)),
            'fontconfig_file' => $fontconfigFile,
        ];
    }

    public function generateThumbnail(string $videoFullPath, string $thumbnailFullPath): bool
    {
        $ffmpeg = $this->ffmpegBinary();
        // -threads 1: same shared-host LVE thread-limit issue as the main
        // render -- a single-frame image encoder still frame-threads by
        // default and can fail to open on this host otherwise.
        $cmd = sprintf(
            '%s -y -ss 00:00:01 -i %s -frames:v 1 -vf %s -threads 1 %s 2>&1',
            escapeshellcmd($ffmpeg),
            escapeshellarg($videoFullPath),
            escapeshellarg('scale=480:854:force_original_aspect_ratio=increase,crop=480:854'),
            escapeshellarg($thumbnailFullPath)
        );
        exec($cmd, $output, $exitCode);
        return $exitCode === 0 && file_exists($thumbnailFullPath);
    }

    /**
     * Re-encodes an admin-uploaded background video into a known-clean
     * baseline (h264/yuv420p/25fps/SAR 1:1, capped at 1920px on the long
     * side) immediately at upload time. Whatever odd frame rate, pixel
     * aspect ratio, or codec profile the original file carried (phone
     * export, downloaded stock clip, screen recording, etc.) -- the exact
     * class of thing that made libx264 refuse to open the encoder during
     * render() -- gets normalized away once here, so every future render
     * using this template starts from input FFmpeg already knows it can
     * handle, instead of re-encountering the same failure on every use.
     *
     * @throws RuntimeException if the source file can't be read/encoded at all.
     */
    public function normalizeBackground(string $inputFullPath, string $outputFullPath): void
    {
        $ffmpeg = $this->ffmpegBinary();

        // -threads 1 / x264-params threads=1: see the note in render() --
        // the shared host's LVE thread limit, not the input file's own
        // format, is what makes libx264 fail to open here.
        $cmd = sprintf(
            '%s -y -i %s -vf %s -an -r 25 -c:v libx264 -preset fast -crf 20 -pix_fmt yuv420p -threads 1 -x264-params threads=1 -movflags +faststart %s 2>&1',
            escapeshellcmd($ffmpeg),
            escapeshellarg($inputFullPath),
            escapeshellarg("scale='min(1920,iw)':'min(1920,ih)':force_original_aspect_ratio=decrease:force_divisible_by=2,setsar=1"),
            escapeshellarg($outputFullPath)
        );

        exec($cmd, $outputLines, $exitCode);

        if ($exitCode !== 0 || !file_exists($outputFullPath) || filesize($outputFullPath) < 1000) {
            $tail = implode("\n", array_slice($outputLines, -40));
            throw new RuntimeException('Could not process this video (exit ' . $exitCode . '): ' . $tail);
        }
    }
}
