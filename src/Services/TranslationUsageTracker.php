<?php

namespace PutTranslations\Laravel\Services;

use Illuminate\Support\Facades\File;

class TranslationUsageTracker
{
    protected static $usedKeys = [];

    public static function markAsUsed($key)
    {
        self::$usedKeys[$key] = true;
    }

    public static function getUsedKeys()
    {
        return array_keys(self::$usedKeys);
    }

    public static function saveUsedKeys()
    {
        $path = storage_path('app/used_translations.json');
        File::put($path, json_encode(self::getUsedKeys(), JSON_PRETTY_PRINT));
    }
}