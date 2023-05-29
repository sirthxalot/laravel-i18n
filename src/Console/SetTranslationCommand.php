<?php

namespace Sirthxalot\Laravel\I18n\Console;

class SetTranslationCommand extends I18nCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:set-translation {tKey?} {locale?} {message?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update a translation message on current driver';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $key = $this->askForTranslationKey();
        $locale = $this->askForLocale();
        $isUpdate = $this->confirmTranslationUpdate($key, $locale);
        $message = $this->askForMessage();

        try {
            $this->i18n->setTranslation($key, $locale, $message);

            if ($isUpdate) {
                $this->info(__('✅  Translation has been updated successfully.'));
            } else {
                $this->info(__('✅  Translation has been created successfully.'));
            }
        } catch (\Exception $e) {
            $this->info(__('Translation Key: :key', compact('key')));
            $this->info(__('Locale: :locale', compact('locale')));
            $this->info(__('Message: :message', compact('message')));

            $this->newLine();

            $this->error(__('⛔️ Whoops something went wrong.'));
        }
    }

    /**
     * Ask user which locale to use in a loop till we have it.
     *
     * The user must confirm creation of non-existing languages.
     */
    protected function askForLocale(): string
    {
        $locale = $this->argument('locale');

        if (! $locale) {
            do {
                $locale = $this->ask(__('Which locale is the translation for?'));
            } while (! $locale);
        }

        if (! $this->i18n->languageExists($locale)) {
            $this->info(__('The language does not exist yet.'));

            if (! $this->confirm(__('Do you really want to add a new language?'))) {
                exit();
            }
        }

        return $locale;
    }

    /**
     * Ask user which message to use. Messages can be empty but
     * must be confirmed before continue.
     */
    protected function askForMessage(): string
    {
        $message = $this->argument('message');

        if (! $message) {
            $message = $this->ask(__('Which message do you want to use?'));
        }

        if (! $message) {
            $this->info(__('Your translation message is empty.'));

            if (! $this->confirm(__('Is this okay?'))) {
                exit();
            }

            $message = '';
        }

        return $message;
    }

    /**
     * Ask user which translation key to use in a loop till we have it.
     */
    protected function askForTranslationKey(): string
    {
        $key = $this->argument('tKey');

        if (! $key) {
            do {
                $this->info(__('A single translation key can be any type of text e.g. "Hello :Name!".'));
                $this->info(__('A group translation key follows the array dot convention e.g. "animals.dog".'));
                $this->info(__('The same is true for vendors but they begin with their namespace followed by :: e.g. "i18n::animals.dog".'));

                $key = $this->ask(__('Which translation key do you want to use?'));
            } while (! $key);

            $this->info(__('Great choice we assume this is a ":type" translation key.', ['type' => $this->i18n->translationKeyType($key)]));
        }

        return $key;
    }

    /**
     * User must confirm translation update before continue.
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     */
    protected function confirmTranslationUpdate(string $key, string $locale): bool
    {
        $isUpdate = false;

        if ($this->i18n->translationExists($key, $locale)) {
            $this->info(__('The translation already exists.'));

            if (! $this->confirm(__('Do you still want to continue?'))) {
                exit();
            }

            $isUpdate = true;
        }

        return $isUpdate;
    }
}
