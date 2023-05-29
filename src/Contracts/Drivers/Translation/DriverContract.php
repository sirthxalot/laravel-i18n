<?php

namespace Sirthxalot\Laravel\I18n\Contracts\Drivers\Translation;

use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageCreated;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageDeleted;

interface DriverContract
{
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
    public function addLanguage(string $locale): bool;

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
    public function addTranslation(string $key, string $locale, string $message = ''): bool;

    /**
     * Get all languages available within the service.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => "English"]
     */
    public function allLanguages(): array;

    /**
     * Get all translations available within the application.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => ['i18n::animals.dog' => "A dog barks."]]
     */
    public function allTranslations(): array;

    /**
     * Remove an existing language.
     *
     * @since  1.0.0
     * @see    LanguageDeleted  Language Deleted Event
     *
     * @param  string  $locale  "en_US"
     */
    public function removeLanguage(string $locale): bool;

    /**
     * Delete an existing translation.
     *
     * @since  1.0.0
     * @see    TranslationDeleted  Translation Deleted Event
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     */
    public function removeTranslation(string $key, string $locale): bool;

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
    public function updateTranslation(string $key, string $locale, string $message = ''): bool;
}
