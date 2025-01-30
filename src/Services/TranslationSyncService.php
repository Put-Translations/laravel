<?php

namespace PutTranslations\Laravel\Services;

use PutTranslations\Laravel\Services\TranslationUsageTracker;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class TranslationSyncService
{
    protected function fetchTranslationsFromApi($strings)
    {
        $apiUrl = config('translations.api_url');
        $apiKey = config('translations.api_key');
        $sourceLocale = config('translation.source_locale', 'en');
        $targetLocales = config('translation.target_locales', ['es', 'pt']);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post($apiUrl, [
            'source_language' => $sourceLocale,
            'target_languages' => $targetLocales,
            'strings' => $strings,
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new \Exception("API request failed: " . $response->body());
        }
    }

    protected function saveTranslations($translations)
    {
        $outputPath = base_path('resources/lang');
        $outputFormat = 'json';

        foreach ($translations as $locale => $strings) {
            $localePath = $outputPath;

            if (!File::isDirectory($localePath)) {
                File::makeDirectory($localePath, 0755, true);
            }

            $filePath = $localePath . DIRECTORY_SEPARATOR . $locale . '.json';
            File::put($filePath, json_encode($strings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo "Translations saved to: " . $filePath . "\n";
        }
    }
    public function sync()
    {
        try {
            $strings = $this->extractTranslatableStrings();
            $translations = $this->fetchTranslationsFromApi($strings);
            $this->saveTranslations($translations);
        } catch (\Exception $e) {
            echo "An error occurred during translation sync: " . $e->getMessage() . "\n";
            throw $e;
        }
    }

    protected function extractTranslatableStrings()
    {
        $strings = [];
        $directories = config('translations.scan_directories', ['app', 'resources']);
        $patterns = [
            '/\b__\([\'"](.+?)[\'"]\)/',  // __('string')
            '/\btrans\([\'"](.+?)[\'"]\)/',  // trans('string')
            '/@lang\([\'"](.+?)[\'"]\)/',  // @lang('string')
            '/\{\{\s*__\([\'"](.+?)[\'"]\)\s*\}\}/',  // {{ __('string') }}
            '/\{\{\s*trans\([\'"](.+?)[\'"]\)\s*\}\}/',  // {{ trans('string') }}
            '/\_\([\'"](.+?)[\'"]\)/',  // _('string') for gettext
            '/gettext\([\'"](.+?)[\'"]\)/',  // gettext('string')
            '/\bt\([\'"](.+?)[\'"]\)/',  // t('string') in PHP files
            '/\{\{(\s*)t\([\'"](.+?)[\'"](\))\s*\}\}/',  // {{ t('string') }} or {{t('string')}} in Blade files
        ];

        foreach ($directories as $directory) {
            $path = base_path($directory);

            if (!is_dir($path)) {
                echo "Warning: Directory does not exist: " . $path . "\n";
                continue;
            }

            $files = $this->getFilesRecursively($path);

            foreach ($files as $file) {
                $content = file_get_contents($file);

                foreach ($patterns as $pattern) {
                    if (preg_match_all($pattern, $content, $matches)) {
                        $strings = array_merge($strings, $matches[1]);
                    }
                }
            }
        }

        $usedKeys = $this->getTrackedTranslations();
        $strings = array_merge($strings, $usedKeys);

        return array_unique($strings);
    }

    protected function getTrackedTranslations()
    {
        return TranslationUsageTracker::getUsedKeys();
    }

    private function getFilesRecursively($dir)
    {
        $files = [];
        $scan = scandir($dir);

        foreach ($scan as $file) {
            if ($file === '.' || $file === '..') continue;
            $path = realpath($dir . DIRECTORY_SEPARATOR . $file);
            if (!is_dir($path)) {
                $files[] = $path;
            } else {
                $files = array_merge($files, $this->getFilesRecursively($path));
            }
        }

        return $files;
    }
}