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
 * placeholder). Instead, the text is built as an SVG (with the right font
 * embedded directly as base64, so it needs no fontconfig setup), which
 * FFmpeg rasterizes through its own bundled librsvg decoder -- that path
 * renders through Pango internally and shapes Bengali and Arabic
 * correctly (also confirmed empirically). The rasterized text PNG is then
 * composited onto the background video with the `overlay` filter.
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
     * text any other way across renderers). The font file is embedded as
     * base64 inside the SVG itself so librsvg never needs a system
     * fontconfig entry for it.
     */
    protected function buildSvg(string $text, string $color, int $fontSize, bool $rtl): string
    {
        $fontPath = $this->fontPathForText($text);
        $fontBase64 = base64_encode(file_get_contents($fontPath));
        $lines = $this->wrapLines($text, $rtl ? 18 : 22);

        $lineHeight = (int) round($fontSize * 1.45);
        $textBlockHeight = count($lines) * $lineHeight;
        $boxPaddingY = 48;
        $boxPaddingX = 56;
        $boxWidth = self::CANVAS_WIDTH - 120;
        $boxHeight = $textBlockHeight + ($boxPaddingY * 2);
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
@font-face {
  font-family: 'VideoMakerFont';
  src: url(data:font/ttf;base64,{$fontBase64}) format('truetype');
}
text { font-family: 'VideoMakerFont'; }
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

        $cmd = sprintf(
            '%s -y -stream_loop -1 -i %s -i %s -t %d -filter_complex %s -an -r 25 -c:v libx264 -preset fast -crf 23 -pix_fmt yuv420p -movflags +faststart %s 2>&1',
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

    public function generateThumbnail(string $videoFullPath, string $thumbnailFullPath): bool
    {
        $ffmpeg = $this->ffmpegBinary();
        $cmd = sprintf(
            '%s -y -ss 00:00:01 -i %s -frames:v 1 -vf %s %s 2>&1',
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

        $cmd = sprintf(
            '%s -y -i %s -vf %s -an -r 25 -c:v libx264 -preset fast -crf 20 -pix_fmt yuv420p -movflags +faststart %s 2>&1',
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
