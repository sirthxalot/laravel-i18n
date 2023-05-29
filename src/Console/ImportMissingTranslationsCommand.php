<?php

namespace Sirthxalot\Laravel\I18n\Console;

class ImportMissingTranslationsCommand extends I18nCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:import-missing-translations {language?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import missing translations with empty messages';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $language = $this->argument('language') ?: false;

        try {
            $this->i18n->importMissingTranslations($language);

            $this->info(__('✅  Missing translations imported successfully.'));
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
