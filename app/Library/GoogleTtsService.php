<?php

namespace App\Library;

use RuntimeException;

/**
 * Synthesizes speech via Google Cloud's Text-to-Speech REST API (plain
 * API-key auth, no service-account JSON -- simplest thing a non-technical
 * admin can paste into a settings field). Used as the Video Maker's
 * fallback voice whenever the text isn't a recognized Quran ayah (see
 * QuranAudioMatcher), or isn't Arabic at all.
 */
class GoogleTtsService
{
    protected const ENDPOINT = 'https://texttospeech.googleapis.com/v1/text:synthesize';

    public function __construct(protected string $apiKey)
    {
    }

    protected function isArabic(string $text): bool
    {
        return (bool) preg_match('/\p{Arabic}/u', $text);
    }

    protected function isBengali(string $text): bool
    {
        return (bool) preg_match('/\p{Bengali}/u', $text);
    }

    /**
     * Google has no separate Bangladeshi-Bengali voice -- bn-IN is the
     * closest available and is still clearly understandable, just with a
     * slightly different regional accent.
     */
    protected function voiceForText(string $text): array
    {
        if ($this->isArabic($text)) {
            return ['languageCode' => 'ar-XA', 'name' => 'ar-XA-Standard-A'];
        }
        if ($this->isBengali($text)) {
            return ['languageCode' => 'bn-IN', 'name' => 'bn-IN-Standard-A'];
        }
        return ['languageCode' => 'en-US', 'name' => 'en-US-Standard-C'];
    }

    /**
     * Synthesizes $text to speech and writes the MP3 bytes to $outputPath.
     *
     * @throws RuntimeException on any curl/HTTP/API failure -- the message
     *         always includes Google's own error text when there is one,
     *         so a bad API key or disabled API shows up clearly.
     */
    public function synthesize(string $text, string $outputPath): void
    {
        $voice = $this->voiceForText($text);

        $body = json_encode([
            'input' => ['text' => $text],
            'voice' => $voice,
            'audioConfig' => ['audioEncoding' => 'MP3'],
        ]);

        $ch = curl_init(self::ENDPOINT . '?key=' . urlencode($this->apiKey));
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ]);
        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            throw new RuntimeException('Google TTS request failed (curl): ' . $curlError);
        }

        $decoded = json_decode($response, true);

        if ($httpCode !== 200 || !isset($decoded['audioContent'])) {
            $message = $decoded['error']['message'] ?? $response;
            throw new RuntimeException('Google TTS API error (HTTP ' . $httpCode . '): ' . $message);
        }

        $audioBytes = base64_decode($decoded['audioContent']);
        if ($audioBytes === false || strlen($audioBytes) < 100) {
            throw new RuntimeException('Google TTS returned an unexpectedly small/invalid audio payload.');
        }

        file_put_contents($outputPath, $audioBytes);
    }
}
