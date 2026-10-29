<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $guarded = [];

    protected $casts = ['value' => 'array'];

    /** Fetch one setting; translatable values are stored as {ar, en}. */
    public static function get(string $key, mixed $default = null, bool $localized = false): mixed
    {
        $all = Cache::rememberForever('settings', fn () => static::pluck('value', 'key')->all());
        $v = $all[$key] ?? null;
        if ($v === null) {
            return $default;
        }
        if ($localized && is_array($v)) {
            return $v[app()->getLocale()] ?? $v['ar'] ?? $default;
        }

        return $v;
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('settings');
    }
}
