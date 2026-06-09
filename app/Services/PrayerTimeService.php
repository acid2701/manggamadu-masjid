<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PrayerTimeService
{
    /**
     * Cache duration in seconds (24 hours).
     */
    private const CACHE_TTL = 86400;

    /**
     * Aladhan API base URL.
     */
    private const API_BASE_URL = 'https://api.aladhan.com/v1';

    /**
     * Calculation method (20 = Kementerian Agama RI).
     */
    private const CALCULATION_METHOD = 20;

    public function __construct(
        private SettingService $settingService,
    ) {}

    /**
     * Get today's prayer times.
     *
     * @return array{fajr: string, dhuhr: string, asr: string, maghrib: string, isha: string, date: string}|null
     */
    public function getToday(): ?array
    {
        $now = now();
        $monthly = $this->getMonthly($now->year, $now->month);

        if ($monthly === null) {
            return null;
        }

        $today = $now->day - 1; // API returns 0-indexed array

        return $monthly[$today] ?? null;
    }

    /**
     * Get prayer times for an entire month.
     *
     * @return array<int, array{fajr: string, dhuhr: string, asr: string, maghrib: string, isha: string, date: string}>|null
     */
    public function getMonthly(int $year, int $month): ?array
    {
        $latitude = $this->settingService->get('latitude', '-7.4833');
        $longitude = $this->settingService->get('longitude', '110.8167');

        $cacheKey = "prayer_times_{$year}_{$month}_{$latitude}_{$longitude}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($year, $month, $latitude, $longitude) {
            return $this->fetchFromApi($year, $month, $latitude, $longitude);
        });
    }

    /**
     * Fetch prayer times from Aladhan API.
     *
     * @return array<int, array{fajr: string, dhuhr: string, asr: string, maghrib: string, isha: string, date: string}>|null
     */
    private function fetchFromApi(int $year, int $month, string $latitude, string $longitude): ?array
    {
        try {
            $response = Http::timeout(10)->get(self::API_BASE_URL."/calendar/{$year}/{$month}", [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'method' => self::CALCULATION_METHOD,
            ]);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json('data');

            if (empty($data)) {
                return null;
            }

            return collect($data)->map(function (array $day) {
                $timings = $day['timings'];
                $date = $day['date']['gregorian'];

                return [
                    'fajr' => $this->cleanTime($timings['Fajr']),
                    'dhuhr' => $this->cleanTime($timings['Dhuhr']),
                    'asr' => $this->cleanTime($timings['Asr']),
                    'maghrib' => $this->cleanTime($timings['Maghrib']),
                    'isha' => $this->cleanTime($timings['Isha']),
                    'date' => $date['date'],
                ];
            })->values()->toArray();
        } catch (\Exception $e) {
            report($e);

            return null;
        }
    }

    /**
     * Clean time string from API (remove timezone info like " (WIB)").
     */
    private function cleanTime(string $time): string
    {
        return trim(preg_replace('/\s*\(.*\)/', '', $time));
    }

    /**
     * Get the next prayer time from today's schedule.
     *
     * @return array{name: string, time: string}|null
     */
    public function getNextPrayer(): ?array
    {
        $today = $this->getToday();

        if ($today === null) {
            return null;
        }

        $now = now()->format('H:i');
        $prayers = [
            'Subuh' => $today['fajr'],
            'Dzuhur' => $today['dhuhr'],
            'Ashar' => $today['asr'],
            'Maghrib' => $today['maghrib'],
            'Isya' => $today['isha'],
        ];

        foreach ($prayers as $name => $time) {
            if ($time > $now) {
                return ['name' => $name, 'time' => $time];
            }
        }

        // All prayers have passed, next is Subuh tomorrow
        return ['name' => 'Subuh', 'time' => $today['fajr']];
    }
}
