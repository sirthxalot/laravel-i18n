<?php

namespace Sirthxalot\Laravel\I18n\Console;

class ListMissingTranslationsCommand extends I18nCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:list-missing-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all missing translation keys found on the current driver';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $allLanguages = $this->i18n->allLanguages();

        $table = [];

        foreach ($allLanguages as $locale => $name) {
            try {
                $missingTranslations = $this->i18n->allMissingTranslations($locale);

                foreach ($missingTranslations as $tKey => $empty) {
                    $table[] = ['locale' => $locale, 'translation_key' => $tKey];
                }
            } catch (\Exception $e) {
                $this->error(__('⛔️ Something went wrong.'));

                exit();
            }
        }

        $this->newLine();

        $this->table([__('LOCALE'), __('TRANSLATION KEY')], $table);
    }
}
