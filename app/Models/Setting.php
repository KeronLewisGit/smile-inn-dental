<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/** Editable clinic settings (hours, contact details, booking rules). Falls back to config('clinic.*'). */
#[Fillable(['key', 'value'])]
class Setting extends Model
{
    protected $primaryKey = 'key';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return ['value' => 'array'];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $cache = app()->bound('settings.cache') ? app('settings.cache') : [];
        if (! array_key_exists($key, $cache)) {
            $cache[$key] = static::find($key)?->value;
            app()->instance('settings.cache', $cache);
        }

        return $cache[$key] ?? config("clinic.{$key}", $default);
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        app()->instance('settings.cache', []);
    }
}
