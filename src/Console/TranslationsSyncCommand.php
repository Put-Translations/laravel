<?php

namespace PutTranslations\Laravel\Console;

use Illuminate\Console\Command;
use PutTranslations\Laravel\Services\TranslationSyncService;

class TranslationsSyncCommand extends Command
{
    protected $signature = 'put:sync';

    protected $description = 'Sync translations with the external API';

    protected $translationSyncService;

    public function __construct(TranslationSyncService $translationSyncService)
    {
        parent::__construct();
        $this->translationSyncService = $translationSyncService;
    }

    public function handle()
    {
        $this->info('Starting translation sync...');

        try {
            $this->translationSyncService->sync();
            $this->info('Translation sync completed successfully.');
        } catch (\Exception $e) {
            $this->error('An error occurred during translation sync: ' . $e->getMessage());
        }
    }
}