<?php

namespace App\Library;

/**
 * Hadith reference data (storage/app/hadith-data.json and
 * hadith-collections.json) is NOT bundled with the app -- same reasoning
 * as QuranAudioMatcher's ayah data: it's fetched once, directly by the
 * production server, from a public hadith text API by the
 * /system-setup-hadith-data one-off route, rather than shipped as a
 * static asset.
 */
class HadithMatcher
{
    /** key (matches the source API's collection slug) => Bengali display name. */
    public const COLLECTIONS = [
        'bukhari' => 'সহীহ বুখারী',
        'muslim' => 'সহীহ মুসলিম',
        'abudawud' => 'সুনানে আবু দাউদ',
        'tirmidhi' => 'জামে তিরমিযী',
        'nasai' => 'সুনানে নাসাঈ',
        'ibnmajah' => 'সুনানে ইবনে মাজাহ',
        'malik' => 'মুয়াত্তা মালিক',
        'riyadussalihin' => 'রিয়াদুস সলিহীন',
    ];

    protected static ?array $rows = null;
    protected static ?array $index = null;

    protected function dataFile(): string
    {
        return storage_path('app/hadith-data.json');
    }

    protected function collectionsFile(): string
    {
        return storage_path('app/hadith-collections.json');
    }

    protected function loadRows(): array
    {
        if (static::$rows !== null) {
            return static::$rows;
        }
        if (!file_exists($this->dataFile())) {
            return static::$rows = [];
        }
        $decoded = json_decode((string) file_get_contents($this->dataFile()), true);
        return static::$rows = is_array($decoded) ? $decoded : [];
    }

    protected function loadIndex(): array
    {
        if (static::$index !== null) {
            return static::$index;
        }
        $index = [];
        foreach ($this->loadRows() as $row) {
            $index[($row['collection'] ?? '') . ':' . ($row['number'] ?? '')] = $row;
        }
        return static::$index = $index;
    }

    /**
     * @return array<int, array{key:string, name:string, count:int}>
     */
    public function collectionList(): array
    {
        if (!file_exists($this->collectionsFile())) {
            return [];
        }
        $decoded = json_decode((string) file_get_contents($this->collectionsFile()), true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @return array{arabic:string, translation:string, reference:string}|null
     */
    public function getHadith(string $collection, int $number): ?array
    {
        $row = $this->loadIndex()[$collection . ':' . $number] ?? null;
        if (!$row) {
            return null;
        }

        return [
            'arabic' => $row['arabic'] ?? '',
            'translation' => $row['translation_bn'] ?? '',
            'reference' => $row['reference'] ?? '',
        ];
    }
}
