<?php

namespace App\Library;

/**
 * Two ways in: a fuzzy text match (for free-typed text that happens to be
 * an ayah) and a direct surah/ayah-range lookup (for the explicit Quran-
 * ayah picker, which is exact by construction and never needs matching).
 * Either way, the result is a real reciter's recitation audio, downloaded
 * from everyayah.com and cached locally -- not a synthetic voice reading
 * the Arabic aloud.
 *
 * Fuzzy matching is exact-only on a diacritic-stripped, alef/hamza-
 * normalized copy of the text (both the stored ayah and the user's input
 * go through the same normalize() before comparing), since typed input
 * usually carries full tashkeel (vowel marks) while the stored reference
 * text doesn't. Only a single, whole ayah is matched this way -- a
 * partial quote or several ayahs typed together won't match, and falls
 * through to the AI voice instead.
 *
 * The ayah reference data (storage/app/quran-ayahs.json and
 * quran-surahs.json) is NOT bundled with the app -- it's fetched once
 * from a public Quran text API by the /system-setup-quran-audio one-off
 * route, because this text is large (6,236 ayahs) and best fetched
 * directly by the production server.
 */
class QuranAudioMatcher
{
    /**
     * key => [everyayah.com folder name, display name]. Verify these
     * folder names on the real server via /system-check-reciters before
     * trusting one in production -- everyayah.com isn't reachable from
     * this sandbox to confirm them directly.
     */
    // Confirmed live via /system-check-reciters on the real server --
    // 'Abdul_Basit_Murattal_128kbps' 404'd and was dropped; add him back
    // with the correct folder name if/when one is confirmed.
    public const RECITERS = [
        'alafasy' => ['Alafasy_128kbps', 'Mishary Rashid Alafasy'],
        'husary' => ['Husary_128kbps', 'Mahmoud Khalil Al-Husary'],
        'minshawy' => ['Minshawy_Murattal_128kbps', 'Mohamed Siddiq El-Minshawi'],
        'sudais' => ['Abdurrahmaan_As-Sudais_192kbps', 'Abdul Rahman Al-Sudais'],
        'shuraim' => ['Saood_ash-Shuraym_128kbps', 'Saud Al-Shuraim'],
    ];

    public const DEFAULT_RECITER = 'alafasy';

    protected static ?array $lookup = null;
    protected static ?array $surahCounts = null;
    protected static ?array $ayahTextBySurahAyah = null;

    protected function dataFile(): string
    {
        return storage_path('app/quran-ayahs.json');
    }

    protected function surahsFile(): string
    {
        return storage_path('app/quran-surahs.json');
    }

    protected function cacheDir(): string
    {
        return storage_path('app/quran-audio-cache');
    }

    protected function reciterFolder(string $reciterKey): string
    {
        return self::RECITERS[$reciterKey][0] ?? self::RECITERS[self::DEFAULT_RECITER][0];
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

    protected function loadRows(): array
    {
        static $rows = null;
        if ($rows !== null) {
            return $rows;
        }
        if (!file_exists($this->dataFile())) {
            return $rows = [];
        }
        $decoded = json_decode((string) file_get_contents($this->dataFile()), true);
        return $rows = is_array($decoded) ? $decoded : [];
    }

    protected function loadLookup(): array
    {
        if (static::$lookup !== null) {
            return static::$lookup;
        }

        static::$lookup = [];
        foreach ($this->loadRows() as $row) {
            if (isset($row['normalized'], $row['surah'], $row['ayah']) && $row['normalized'] !== '') {
                static::$lookup[$row['normalized']] = [(int) $row['surah'], (int) $row['ayah']];
            }
        }

        return static::$lookup;
    }

    protected function loadAyahTextIndex(): array
    {
        if (static::$ayahTextBySurahAyah !== null) {
            return static::$ayahTextBySurahAyah;
        }

        static::$ayahTextBySurahAyah = [];
        foreach ($this->loadRows() as $row) {
            if (isset($row['surah'], $row['ayah'], $row['text'])) {
                static::$ayahTextBySurahAyah[(int) $row['surah'] . ':' . (int) $row['ayah']] = $row['text'];
            }
        }

        return static::$ayahTextBySurahAyah;
    }

    protected static ?array $rowsBySurahAyah = null;

    protected function loadRowIndex(): array
    {
        if (static::$rowsBySurahAyah !== null) {
            return static::$rowsBySurahAyah;
        }

        static::$rowsBySurahAyah = [];
        foreach ($this->loadRows() as $row) {
            if (isset($row['surah'], $row['ayah'])) {
                static::$rowsBySurahAyah[(int) $row['surah'] . ':' . (int) $row['ayah']] = $row;
            }
        }

        return static::$rowsBySurahAyah;
    }

    /**
     * Per-ayah display data for a surah/ayah range -- the Uthmani
     * (traditional Mushaf script) text and Bengali translation for each
     * ayah, used to build the Quran-style video instead of the plain
     * undiacritized matching text. Falls back to the simple script if
     * the Uthmani edition wasn't fetched, and to an empty translation if
     * the Bengali edition wasn't fetched -- either way the video still
     * renders, just without that polish.
     *
     * @return array<int, array{ayah:int, uthmani:string, translation:string}>|null
     */
    public function ayahRangeDisplayData(int $surah, int $from, int $to): ?array
    {
        $index = $this->loadRowIndex();
        $result = [];
        for ($ayah = $from; $ayah <= $to; $ayah++) {
            $row = $index[$surah . ':' . $ayah] ?? null;
            if (!$row) {
                return null;
            }
            $result[] = [
                'ayah' => $ayah,
                'uthmani' => $row['text_uthmani'] ?? $row['text'] ?? '',
                'translation' => $row['translation_bn'] ?? '',
            ];
        }

        return $result;
    }

    public function surahNameArabic(int $surah): ?string
    {
        foreach ($this->surahList() as $s) {
            if ((int) ($s['number'] ?? 0) === $surah) {
                return $s['name_arabic'] ?? null;
            }
        }
        return null;
    }

    /**
     * @return array<int, array{number:int, name_arabic:string, name_english:string, name_translation:string, ayah_count:int}>
     */
    public function surahList(): array
    {
        if (static::$surahCounts !== null) {
            return static::$surahCounts;
        }

        if (!file_exists($this->surahsFile())) {
            return static::$surahCounts = [];
        }

        $decoded = json_decode((string) file_get_contents($this->surahsFile()), true);
        return static::$surahCounts = is_array($decoded) ? $decoded : [];
    }

    /**
     * The actual Arabic text for a surah/ayah range, straight from the
     * fetched reference data -- used as the source of truth for what
     * goes in the video, never the client's own copy of it.
     */
    public function ayahRangeText(int $surah, int $from, int $to): ?string
    {
        $index = $this->loadAyahTextIndex();
        $parts = [];
        for ($ayah = $from; $ayah <= $to; $ayah++) {
            $key = $surah . ':' . $ayah;
            if (!isset($index[$key])) {
                return null;
            }
            $parts[] = $index[$key];
        }

        return $parts === [] ? null : implode(' ', $parts);
    }

    /**
     * A single ayah's recitation audio (downloading and caching it on
     * first use), for the slide-by-slide Quran video where each ayah
     * gets its own synced segment instead of one combined track.
     */
    public function singleAyahAudio(int $surah, int $ayah, string $reciterKey): ?string
    {
        return $this->downloadAyah($surah, $ayah, $reciterKey);
    }

    protected function downloadAyah(int $surah, int $ayah, string $reciterKey): ?string
    {
        if (!is_dir($this->cacheDir())) {
            mkdir($this->cacheDir(), 0755, true);
        }

        $cachedPath = $this->cacheDir() . '/' . $reciterKey . '_' . $surah . '_' . $ayah . '.mp3';
        if (file_exists($cachedPath) && filesize($cachedPath) > 1000) {
            return $cachedPath;
        }

        $url = sprintf(
            'https://everyayah.com/data/%s/%03d%03d.mp3',
            $this->reciterFolder($reciterKey),
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

    /**
     * Explicit surah/ayah-range lookup for the Quran-ayah picker -- exact
     * by construction, no text matching involved. Concatenates each
     * ayah's recitation (downloading/caching any not already cached) into
     * one audio file via ffmpeg's concat demuxer.
     *
     * @throws \RuntimeException if any ayah's audio can't be fetched, or
     *         ffmpeg concatenation fails.
     */
    /**
     * Returns a local file path to the matched ayah's recitation audio
     * (downloading and caching it on first use), or null if the text
     * doesn't exactly match a known single ayah -- including when the
     * reference data hasn't been set up yet via /system-setup-quran-audio.
     */
    public function findAyahAudio(string $text, string $reciterKey = self::DEFAULT_RECITER): ?string
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

        return $this->downloadAyah($surah, $ayah, $reciterKey);
    }
}
