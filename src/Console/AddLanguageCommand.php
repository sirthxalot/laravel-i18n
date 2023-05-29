<?php

namespace Sirthxalot\Laravel\I18n\Console;

class AddLanguageCommand extends I18nCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:add-language {language?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new language if it does not exist';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $locale = $this->askForLocale();

        try {
            $this->i18n->addLanguage($locale);

            $this->info(__('✅  New language has been created successfully.'));
        } catch (\Exception $e) {
            $this->error(__('⛔️ The language could not been created.'));

            if ($this->i18n->languageExists($locale)) {
                $this->info(__('➡️ Language already exists.'));
            } else {
                $this->info(__('➡️  Invalid data follow convention e.g. "eng_US".'));
            }
        }
    }

    /**
     * Ask user which locale to use in a loop till we have it.
     */
    protected function askForLocale(): string
    {
        $locale = $this->argument('language');

        if (! $locale) {
            do {
                $this->info(__('A valid locale follows the ISO-15897 convention e.g. "eng_US"!'));

                $locale = $this->ask(__('Which language (locale) do you want to create?'));
            } while (! $locale);
        }

        return $locale;
    }
}
