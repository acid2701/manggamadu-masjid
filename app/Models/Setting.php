<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
    ];

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
    public static function get(string $key, mixed $default = null): mixed
    {
        $settings = static::getAllCached();

        return $settings[$key] ?? $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function set(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        static::clearCache();
    }

    /**
     * Get all settings for a specific group.
     *
     * @return Collection<int, static>
     */
    public static function getByGroup(string $group): Collection
    {
        return static::where('group', $group)->get();
    }

    /**
     * Get all settings as a key-value array (cached).
     *
     * @return array<string, mixed>
     */
    public static function getAllCached(): array
    {
        return Cache::remember(static::CACHE_KEY, static::CACHE_TTL, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear the settings cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
