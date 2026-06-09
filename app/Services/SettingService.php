<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Cache key for all settings.
     */
    private const CACHE_KEY = 'app_settings';

    /**
     * Cache duration in seconds (1 hour).
     */
    private const CACHE_TTL = 3600;

    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->getAllCached();

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value by key.
     */
    public function set(string $key, mixed $value): void
    {
        Setting::where('key', $key)->update(['value' => $value]);
        $this->clearCache();
    }

    /**
     * Get all settings for a specific group.
     *
     * @return Collection<int, Setting>
     */
    public function getByGroup(string $group): Collection
    {
        return Setting::where('group', $group)->get();
    }

    /**
     * Get all settings as a key-value array (cached).
     *
     * @return array<string, mixed>
     */
    public function getAllCached(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear the settings cache.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Check if a boolean setting is enabled.
     */
    public function isEnabled(string $key): bool
    {
        $value = $this->get($key, 'false');

        return in_array($value, ['true', '1', true, 1], true);
    }
}
