<?php

namespace App\Library;

use App\Models\UserVideo;
use RuntimeException;

/**
 * Renders a UserVideo by overlaying its text onto its template's
 * background video with FFmpeg. Output is always 1080x1920 (vertical,
 * matching Reels/Shorts/Status format).
 *
 * Font selection: the text's own script decides the font, not a setting
 * someone has to remember to pick -- Arabic text (Quran verses etc.) uses
 * Noto Naskh Arabic (shaped via the server's libharfbuzz/libfribidi FFmpeg
 * build, confirmed to join Arabic letters correctly); everything else
 * (Bengali or Latin/English) uses Noto Sans Bengali, which has both
 * scripts' glyphs merged into one file so it never needs picking either.
 *
 * Text goes into a temp textfile instead of FFmpeg's inline `text=`
 * option -- this sidesteps drawtext's special-character escaping entirely
 * (colons, quotes, percent signs, backslashes) and keeps user-typed text
 * out of the shell command line, which the ffmpeg binary itself is still
 * invoked with escapeshellarg() throughout regardless.
 */
class VideoRenderer
{
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

    protected function fontForText(string $text): string
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
     */
    protected function wrapText(string $text, int $maxCharsPerLine = 22): string
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

        return implode("\n", $lines);
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
        if (!file_exists($ffmpeg) && trim((string) shell_exec('which ffmpeg 2>/dev/null')) === '') {
            throw new RuntimeException('FFmpeg is not available on this server.');
        }

        $font = $this->fontForText($video->text_content);
        if (!file_exists($font)) {
            throw new RuntimeException('Font file missing: ' . $font);
        }

        $outDir = base_path('uploads/user-videos');
        if (!is_dir($outDir)) {
            mkdir($outDir, 0755, true);
        }

        $wrapped = $this->wrapText($video->text_content);
        $textFile = tempnam(sys_get_temp_dir(), 'vt_') . '.txt';
        file_put_contents($textFile, $wrapped);

        $outputFilename = 'video_' . $video->id . '_' . bin2hex(random_bytes(6)) . '.mp4';
        $outputFullPath = $outDir . '/' . $outputFilename;

        $drawtext = sprintf(
            "drawtext=fontfile=%s:textfile=%s:fontcolor=%s:fontsize=%d:line_spacing=14:x=(w-text_w)/2:y=(h-text_h)/2:box=1:boxcolor=black@0.45:boxborderw=28",
            $this->escapeFilterPath($font),
            $this->escapeFilterPath($textFile),
            ltrim($template->text_color ?: '#ffffff', '#'),
            (int) ($template->font_size ?: 60)
        );

        $vf = 'scale=1080:1920:force_original_aspect_ratio=increase,crop=1080:1920,' . $drawtext;

        $cmd = sprintf(
            '%s -y -stream_loop -1 -i %s -t %d -vf %s -an -c:v libx264 -preset fast -crf 23 -pix_fmt yuv420p -movflags +faststart %s 2>&1',
            escapeshellcmd($ffmpeg),
            escapeshellarg($backgroundPath),
            (int) ($template->duration_seconds ?: 10),
            escapeshellarg($vf),
            escapeshellarg($outputFullPath)
        );

        exec($cmd, $outputLines, $exitCode);
        @unlink($textFile);

        if ($exitCode !== 0 || !file_exists($outputFullPath) || filesize($outputFullPath) < 1000) {
            $tail = implode("\n", array_slice($outputLines, -15));
            throw new RuntimeException('FFmpeg render failed (exit ' . $exitCode . '): ' . $tail);
        }

        return 'uploads/user-videos/' . $outputFilename;
    }

    /**
     * FFmpeg's own filter-graph syntax treats : and \ and ' specially
     * inside filter option values (separate from shell escaping, which
     * escapeshellarg() already handles elsewhere in this class) -- paths
     * going into a filter string need their own escaping on top of that.
     */
    protected function escapeFilterPath(string $path): string
    {
        $escaped = str_replace(['\\', ':', "'"], ['\\\\', '\\:', "\\'"], $path);
        return "'" . $escaped . "'";
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
}
