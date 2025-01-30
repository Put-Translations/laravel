<?php

namespace PutTranslations\Laravel;

use Illuminate\Support\ServiceProvider;
use PutTranslations\Laravel\Console\TranslationsSyncCommand;

class TranslationSyncServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                TranslationsSyncCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/translations.php' => config_path('translations.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/translations.php', 'translations'
        );
    }
}