<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'settings',
        'autoload'
    ];

    public static function deletebByKey($key) {
        return Option::where('key', $key)->delete();
    }

    public static function createByKey($key, $settings, $autoload = 0)
    {
        $find = self::findByKey($key);
        if(!$find) {
            return Option::create([
                'key' => $key,
                'settings' => $settings,
                'autoload' => $autoload
            ]);
        }
        return $find;
    }

    public static function getByKey($key)
    {
        $find = self::where('key', $key)->first();
        if($find) {
            return $find->settings ?? '';
        }
        return "";
    }

    public static function findByKey($key)
    {
        $find = self::where('key', $key)->first();
        if(!$find) {
            return false;
        }

        return $find;
    }

    public static function updateByKey($key, $settings, $autoload = 0)
    {
        $find = self::findByKey($key);
        if($find) {
            return $find->update([
                'settings' => $settings
            ]);
        }

        return Option::create([
            'key' => $key,
            'settings' => $settings,
            'autoload' => $autoload
        ]);
    }
}
