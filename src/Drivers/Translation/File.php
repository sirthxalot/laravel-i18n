<?php

namespace Sirthxalot\Laravel\I18n\Drivers\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Contracts\Drivers\Translation\DriverContract;
use Sirthxalot\Laravel\I18n\Drivers\Translation\Traits\File\Languagable;
use Sirthxalot\Laravel\I18n\Drivers\Translation\Traits\File\Translatable;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageCreated;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageDeleted;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationCreated;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationDeleted;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationUpdated;
use Sirthxalot\Laravel\I18n\Exceptions\Translation\File\TranslationNotFoundException;
use Sirthxalot\Laravel\I18n\Translation;
use Sirthxalot\Laravel\I18n\Translation\Scanner;

class File extends Translation implements DriverContract
{
    use Languagable, Translatable;

    /**
     * Create a new file translation driver instance.
     *
     * @since  1.0.0
     *
     * @param  string  $langDirectory  Absolute path to lang/ directory.
     * @param  string  $currentLocale  "en_US"
     * @param  array  $i18nConfig  ['cache' => false]
     */
    public function __construct(
        protected readonly Filesystem $disk,
        protected readonly string $langDirectory,
        protected readonly string $currentLocale,
        protected readonly Scanner $scanner,
        protected readonly array $i18nConfig
    ) {
        // ...
    }

    /**
     * Create a new language if it does not exist.
     *
     * @since  1.0.0
     * @see    LanguageCreated  Language Created Event
     *
     * @param  string  $locale  "en_US"
     *
     * @throws ValidationException
     */
    public function addLanguage(string $locale): bool
    {
        $this->validateLanguageAdd(compact('locale'));

        $this->ensureLanguageExists($locale);

        if ($this->languageExists($locale)) {
            LanguageCreated::dispatch($locale);
        }

        return $this->languageExists($locale);
    }

    /**
     * Create new translation if it does not exist.
     *
     * @since  1.0.0
     * @see    TranslationCreated  Translation Created Event
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     * @param  string  $message  "A dog barks."
     *
     * @throws ValidationException
     * @throws TranslationNotFoundException
     */
    public function addTranslation(string $key, string $locale, string $message = ''): bool
    {
        $data = compact('key', 'locale', 'message');

        $this->validateTranslationAdd($data);

        if ($this->updateOrCreateSingleTranslation($key, $locale, $message)) {
            TranslationCreated::dispatch($key, $locale, $message);

            return true;
        }

        if ($this->updateOrCreateGroupTranslation($key, $locale, $message)) {
            TranslationCreated::dispatch($key, $locale, $message);

            return true;
        }

        if ($this->updateOrCreateVendorTranslation($key, $locale, $message)) {
            TranslationCreated::dispatch($key, $locale, $message);

            return true;
        }

        return false;
    }

    /**
     * Get all languages available within the service.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => "English"]
     */
    public function allLanguages(): array
    {
        return array_merge(
            $this->scanLangDirectoryForLanguages(),
            $this->scanLangFilesForLanguage(),
            $this->scanVendorLangDirectoriesForLanguages()
        );
    }

    /**
     * Get all translations available within the service.
     *
     * All languages are represented. Each language contains a
     * list of the translation keys and translation messages
     * found for a given language.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => ['i18n::animals.dog' => "A dog barks."]]
     *
     * @throws TranslationNotFoundException
     */
    public function allTranslations(): array
    {
        $allTranslations = $this->scanTranslations();

        // ensure each language is available in translations
        $allLanguages = Collection::make($this->allLanguages());

        $allLanguages = $allLanguages->mapWithKeys(function ($name, $locale) {
            return [$locale => []];
        })->sortKeys();

        $allTranslations = array_merge_recursive(
            $allLanguages->toArray(),
            $allTranslations
        );

        return $allTranslations;
    }

    /**
     * Recursively delete a language directory and file.
     *
     * This method will remove the lang directory and json
     * translation file. Hence, all translations will be
     * deleted as well. Always backup your filesystem before
     * using this method.
     *
     * @since  1.0.0
     * @see    LanguageDeleted  Language Deleted Event
     *
     * @param  string  $locale  "en_US"
     */
    public function removeLanguage(string $locale): bool
    {
        if (! $this->languageExists($locale)) {
            return false;
        }

        $this->deleteLangDirectoryFor($locale);
        $this->deleteLangFileFor($locale);
        $this->deleteVendorLangDirectoryFor($locale);
        $this->deleteVendorLangFileFor($locale);

        if (! $this->languageExists($locale)) {
            LanguageDeleted::dispatch($locale);

            return true;
        }

        return false;
    }

    /**
     * Delete an existing translation.
     *
     * @since  1.0.0
     * @see    TranslationDeleted  Translation Deleted Event
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     *
     * @throws TranslationNotFoundException
     */
    public function removeTranslation(string $key, string $locale): bool
    {
        if (! $this->translationExists($key, $locale)) {
            return false;
        }

        $filepath = $this->getTranslationFilepath($key, $locale);

        if (! $this->disk->exists($filepath)) {
            return false;
        }

        if ($this->deleteSingleTranslation($key, $locale)) {
            TranslationDeleted::dispatch($key, $locale);

            return true;
        }

        if ($this->deleteGroupTranslation($key, $locale)) {
            TranslationDeleted::dispatch($key, $locale);

            return true;
        }

        if ($this->deleteVendorTranslation($key, $locale)) {
            TranslationDeleted::dispatch($key, $locale);

            return true;
        }

        return false;
    }

    /**
     * Update an existing translation.
     *
     * @since  1.0.0
     * @see    TranslationUpdated  Translation Updated Event
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     * @param  string  $message  "A dog barks."
     *
     * @throws ValidationException
     * @throws TranslationNotFoundException
     */
    public function updateTranslation(string $key, string $locale, string $message = ''): bool
    {
        $data = compact('key', 'locale', 'message');

        $this->validateTranslationUpdate($data);

        $messageBefore = $this->allTranslations();
        $messageBefore = $messageBefore[$locale][$key];

        if ($this->updateOrCreateSingleTranslation($key, $locale, $message)) {
            TranslationUpdated::dispatch($key, $locale, $messageBefore, $message);

            return true;
        }

        if ($this->updateOrCreateGroupTranslation($key, $locale, $message)) {
            TranslationUpdated::dispatch($key, $locale, $messageBefore, $message);

            return true;
        }

        if ($this->updateOrCreateVendorTranslation($key, $locale, $message)) {
            TranslationUpdated::dispatch($key, $locale, $messageBefore, $message);

            return true;
        }

        return false;
    }
}
