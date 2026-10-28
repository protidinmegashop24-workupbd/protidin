<?php

namespace App\Library;

/**
 * Matches a user's typed Arabic text against a known Quran ayah, and if
 * it matches, returns a real reciter's recitation audio for it -- so
 * Video Maker videos of an actual verse get authentic Quranic recitation
 * instead of a synthetic voice reading the Arabic aloud.
 *
 * Matching is exact-only on a diacritic-stripped, alef/hamza-normalized
 * copy of the text (both the stored ayah and the user's input go through
 * the same normalize() before comparing), since the user's input usually
 * carries full tashkeel (vowel marks) while the stored reference text
 * doesn't. Only a single, whole ayah is matched in this version -- a
 * partial quote or several ayahs typed together won't match, and falls
 * through to the AI voice instead.
 *
 * The ayah reference data (storage/app/quran-ayahs.json) is NOT bundled
 * with the app -- it's fetched once from a public Quran text API by the
 * /system-setup-quran-audio one-off route, because this text is large
 * (6,236 ayahs) and best fetched directly by the production server.
 * Recitation audio files are downloaded from everyayah.com on first use
 * per ayah and cached locally after that.
 */
class QuranAudioMatcher
{
    protected const RECITER = 'Alafasy_128kbps';

    protected static ?array $lookup = null;

    protected function dataFile(): string
    {
        return storage_path('app/quran-ayahs.json');
    }

    protected function cacheDir(): string
    {
        return storage_path('app/quran-audio-cache');
    }

    public function normalize(string $text): string
    {
        // Strip tashkeel (harakat), superscript alef, Quranic annotation
        // marks, and tatweel.
        $text = preg_replace('/[\x{064B}-\x{065F}\x{0670}\x{06D6}-\x{06ED}\x{0640}]/u', '', $text);
        // Normalize alef variants (أ إ آ ٱ) to a plain alef.
        $text = preg_replace('/[\x{0622}\x{0623}\x{0625}\x{0671}]/u', "\u{0627}", $text);
        // Normalize hamza-on-carrier variants to their plain carrier.
        $text = str_replace(["\u{0624}", "\u{0626}"], ["\u{0648}", "\u{064A}"], $text);
        // Drop anything that isn't an Arabic letter or whitespace
        // (punctuation, verse-end markers, digits).
        $text = preg_replace('/[^\p{Arabic}\s]/u', ' ', $text);
        $text = trim(preg_replace('/\s+/u', ' ', $text));

        return $text;
    }

    protected function loadLookup(): array
    {
        if (static::$lookup !== null) {
            return static::$lookup;
        }

        static::$lookup = [];

        if (!file_exists($this->dataFile())) {
            return static::$lookup;
        }

        $rows = json_decode((string) file_get_contents($this->dataFile()), true);
        if (!is_array($rows)) {
            return static::$lookup;
        }

        foreach ($rows as $row) {
            if (isset($row['normalized'], $row['surah'], $row['ayah']) && $row['normalized'] !== '') {
                static::$lookup[$row['normalized']] = [(int) $row['surah'], (int) $row['ayah']];
            }
        }

        return static::$lookup;
    }

    /**
     * Returns a local file path to the matched ayah's recitation audio
     * (downloading and caching it on first use), or null if the text
     * doesn't exactly match a known single ayah -- including when the
     * reference data hasn't been set up yet via /system-setup-quran-audio.
     */
    public function findAyahAudio(string $text): ?string
    {
        $lookup = $this->loadLookup();
        if (empty($lookup)) {
            return null;
        }

        $normalized = $this->normalize($text);
        if (!isset($lookup[$normalized])) {
            return null;
        }

        [$surah, $ayah] = $lookup[$normalized];

        if (!is_dir($this->cacheDir())) {
            mkdir($this->cacheDir(), 0755, true);
        }

        $cachedPath = $this->cacheDir() . '/' . $surah . '_' . $ayah . '.mp3';
        if (file_exists($cachedPath) && filesize($cachedPath) > 1000) {
            return $cachedPath;
        }

        $url = sprintf(
            'https://everyayah.com/data/%s/%03d%03d.mp3',
            self::RECITER,
            $surah,
            $ayah
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $audio = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($audio === false || $httpCode !== 200 || strlen($audio) < 1000) {
            return null;
        }

        file_put_contents($cachedPath, $audio);

        return $cachedPath;
    }
}
