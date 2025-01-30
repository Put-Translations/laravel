<?php

use PutTranslations\Laravel\Services\TranslationUsageTracker;
use Illuminate\Support\Facades\Lang;

if (!function_exists('t')) {
    function t($key, $replace = [], $locale = null)
    {
        TranslationUsageTracker::markAsUsed($key);
        return __($key, $replace, $locale);
    }
}

// if (!function_exists('__')) {
//     function __($key, $replace = [], $locale = null)
//     {
//         TranslationUsageTracker::markAsUsed($key);
//         return Lang::get($key, $replace, $locale);
//     }
// }

// if (!function_exists('trans')) {
//     function trans($key, $replace = [], $locale = null)
//     {
//         TranslationUsageTracker::markAsUsed($key);
//         return Lang::get($key, $replace, $locale);
//     }
// }

// if (!function_exists('trans_choice')) {
//     function trans_choice($key, $number, array $replace = [], $locale = null)
//     {
//         TranslationUsageTracker::markAsUsed($key);
//         return Lang::choice($key, $number, $replace, $locale);
//     }
// }

// if (!function_exists('__n')) {
//     function __n($key, $number, array $replace = [], $locale = null)
//     {
//         TranslationUsageTracker::markAsUsed($key);
//         return Lang::choice($key, $number, $replace, $locale);
//     }
// }

// if (!function_exists('_')) {
//     function _($key, $replace = [], $locale = null)
//     {
//         TranslationUsageTracker::markAsUsed($key);
//         return Lang::get($key, $replace, $locale);
//     }
// }

// if (!function_exists('gettext')) {
//     function gettext($key)
//     {
//         TranslationUsageTracker::markAsUsed($key);
//         return Lang::get($key);
//     }
// }