<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];

    public static function get($key, $default = null)
    {
        return cache()->remember("setting:$key", 3600, function () use ($key, $default) {
            return optional(
                self::where('key', $key)->first()
            )->value ?? $default;
        });
    }

    public static function set($key, $value, $type = 'string')
    {
        cache()->forget("setting:$key");

        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type
            ]
        );
    }
}