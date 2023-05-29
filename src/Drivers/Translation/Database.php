<?php

namespace Sirthxalot\Laravel\I18n\Drivers\Translation;

use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Contracts\Drivers\Translation\DriverContract;
use Sirthxalot\Laravel\I18n\Contracts\Models\LanguageContract;
use Sirthxalot\Laravel\I18n\Contracts\Models\TranslationContract;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageCreated;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageDeleted;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationCreated;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationDeleted;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationUpdated;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Translation;
use Sirthxalot\Laravel\I18n\Translation\Scanner;

class Database extends Translation implements DriverContract
{
    /**
     * Create new database translation driver instance.
     *
     * @since  1.0.0
     *
     * @param  string  $currentLocale  "en_US"
     * @param  array  $i18nConfig  ['database' => [...]]
     */
    public function __construct(
        protected readonly string $currentLocale,
        protected readonly Scanner $scanner,
        protected readonly array $i18nConfig,
        public LanguageContract $language,
        public TranslationContract $translation,
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
        $data = compact('locale');

        $this->validateLanguageAdd($data);

        $model = $this->language->create($data);

        if ($model->exists()) {
            LanguageCreated::dispatch($locale);

            return true;
        }

        return false;
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
     */
    public function addTranslation(string $key, string $locale, string $message = ''): bool
    {
        $data = compact('key', 'locale', 'message');

        $this->validateTranslationAdd($data);

        $model = $this->translation->create($data);

        if ($model->exists()) {
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
        $languages = $this->language->all();

        $languages = $languages->mapWithKeys(function ($language) {
            return [$language->locale => i18n_lang($language->name)];
        });

        return $languages->toArray();
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
     */
    public function allTranslations(): array
    {
        $translations = [];

        $allLanguages = Language::all();
        $mergeLanguages = [];

        foreach ($allLanguages as $model) {
            $mergeLanguages[$model->locale] = [];
        }

        foreach ($this->translation->all() as $model) {
            $translations[$model->locale][$model->key] = $model->message;
        }

        return array_merge_recursive($mergeLanguages, $translations);
    }

    /**
     * Delete a language and it's translation relationships.
     *
     * This will not detach the relationships instead they will
     * be deleted. Always ensure that you have a backup of your
     * database before using this method.
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

        $language = $this->language->firstWhere('locale', $locale);
        $language->translations()->delete();
        $isDeleted = $language->delete();

        if (true === $isDeleted) {
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
     */
    public function removeTranslation(string $key, string $locale): bool
    {
        if (! $this->translationExists($key, $locale)) {
            return false;
        }

        $translation = $this->translation->where('key', $key)
            ->where('locale', $locale)
            ->firstOrFail();

        $isDeleted = $translation->delete();

        if (true === $isDeleted) {
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
     */
    public function updateTranslation(string $key, string $locale, string $message = ''): bool
    {
        $data = compact('key', 'locale', 'message');

        $this->validateTranslationUpdate($data);

        $model = $this->translation->where('key', $key)->where('locale', $locale)->first();

        $messageBefore = $model->message;

        $isUpdated = $model->update(['message' => $message]);

        if (true === $isUpdated) {
            TranslationUpdated::dispatch($key, $locale, $messageBefore, $message);
        }

        return false;
    }
}
