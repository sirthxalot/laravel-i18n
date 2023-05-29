<?php

namespace Sirthxalot\Laravel\I18n;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Contracts\Drivers\Translation\DriverContract;
use Sirthxalot\Laravel\I18n\Exceptions\Translation\File\TranslationNotFoundException;

abstract class Translation implements DriverContract
{
    /**
     * Get all missing translations for a given language.
     *
     * @since  1.0.0
     *
     * @param  string  $locale  "en_US"
     * @return array ['Page Expired' => ""]
     *
     * @throws TranslationNotFoundException
     */
    public function allMissingTranslations(string $locale): array
    {
        $allTranslations = $this->allTranslationsLoose();

        if (! array_key_exists($locale, $allTranslations)) {
            return $this->scanner->findTranslationStrings();
        }

        $allTranslations = $allTranslations[$locale];

        return array_diff_assoc_recursive(
            $this->scanner->findTranslationStrings(),
            $allTranslations
        );
    }

    /**
     * Get all translation keys available within the application.
     *
     * @since  1.0.0
     *
     * @return array  ['18n::animals.dog' => null]
     */
    public function allTranslationKeys(): array
    {
        $keys = [];
        $allTranslations = $this->allTranslations();

        foreach ($allTranslations as $locales => $array) {
            foreach ($array as $tKey => $tMessage) {
                $keys[$tKey] = null;
            }
        }

        return Collection::make($keys)->sortKeys()->toArray();
    }

    /**
     * Get all translations where the translation key has priority.
     *
     * @since  1.0.0
     *
     * @return array  ['i18n::animals.dog' => ['en' => "A dog barks."]]
     *
     * @throws TranslationNotFoundException
     */
    public function allTranslationsHorizontal(): array
    {
        $remap = [];
        $allTranslations = $this->allTranslationsLoose();

        foreach ($allTranslations as $locale => $array) {
            foreach ($array as $tKey => $tMessage) {
                $remap[$tKey][$locale] = $tMessage;
            }
        }

        return $remap;
    }

    /**
     * Get all translations filled up with missing translation keys.
     *
     * Each missing translation key has a `null` value. Empty string
     * values are also allowed. All languages are represented.
     *
     * @since  1.0.0
     *
     * @return array  ['de' => ['i18n::animals.dog' => null]]
     */
    public function allTranslationsLoose(): array
    {
        $allTranslations = $this->allTranslations();
        $allTranslations = Collection::make($allTranslations);

        $allTranslations = $allTranslations->mapWithKeys(function ($array, $locale) {
            $array = array_merge($this->allTranslationKeys(), $array);

            return [$locale => $array];
        });

        return $allTranslations->toArray();
    }

    /**
     * Import all missing translations for any or given language.
     *
     * If locale is `false` it will import translations into all
     * languages found within the driver.
     *
     * @since  1.0.0
     *
     * @param  false|string  $locale  "en_US"
     *
     * @throws TranslationNotFoundException
     * @throws ValidationException
     */
    public function importMissingTranslations(false|string $locale = false): void
    {
        $languages = $locale ? [$locale => $locale] : $this->allLanguages();

        foreach ($languages as $locale => $name) {
            $missingTranslations = $this->allMissingTranslations($locale);
            foreach ($missingTranslations as $tKey => $empty) {
                $this->setTranslation($tKey, $locale, $empty);
            }
        }
    }

    /**
     * Determine if a language exists or not.
     *
     * @since  1.0.0
     *
     * @param  ?string  $locale  "en_US"
     */
    public function languageExists(?string $locale = null): bool
    {
        return array_key_exists($locale, $this->allLanguages());
    }

    /**
     * Create or update a translation and even language if necessary.
     *
     * @since  1.0.0
     * @see    LanguageCreated  Language Created Event
     * @see    TranslationCreated  Translation Created Event
     * @see    TranslationUpdated  Translation Updated Event
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     * @param  string  $message  "A dog barks."
     *
     * @throws ValidationException
     */
    public function setTranslation(string $key, string $locale, string $message = ''): bool
    {
        if ($this->translationExists($key, $locale)) {
            return $this->updateTranslation($key, $locale, $message);
        }

        if (! $this->languageExists($locale)) {
            $this->addLanguage($locale);
        }

        return $this->addTranslation($key, $locale, $message);
    }

    /**
     * Determine if a translation exists or not.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  ?string  $locale  "en_US"
     */
    public function translationExists(string $key, ?string $locale = null): bool
    {
        $allTranslations = $this->allTranslations();

        if (null === $locale) {
            return array_key_exists($key, $this->allTranslationKeys());
        }

        if (! array_key_exists($locale, $allTranslations)) {
            return false;
        }

        return array_key_exists($key, $allTranslations[$locale]);
    }

    /**
     * Guess the translation type for a given translation key.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @return string  "group", "single" or "vendor"
     */
    public function translationKeyType(string $key): string
    {
        if (Str::contains($key, '::')) {
            return 'vendor';
        }

        if (preg_match('/^[\w-]+(\.[\w-]+)+$/', $key)) {
            return 'group';
        }

        return 'single';
    }

    /**
     * Validate incoming data for language add.
     *
     * @since  1.0.0
     *
     * @param  array  $data  ['locale' => "en"]
     *
     * @throws ValidationException
     */
    public function validateLanguageAdd(array $data): mixed
    {
        request()['locale'] = $data['locale'];

        return app(config('i18n.validation.language.add'));
    }

    /**
     * Validate incoming data for translation add.
     *
     * @since  1.0.0
     *
     * @param  array  $data  [
     *                          'locale' => "en",
     *                          'key' => "i18n:animals.dog",
     *                          'message' => "A dog barks."
     *                       ]
     *
     * @throws ValidationException
     */
    public function validateTranslationAdd(array $data): mixed
    {
        request()['locale'] = $data['locale'];
        request()['key'] = $data['key'];
        request()['message'] = $data['message'];

        return app(config('i18n.validation.translation.add'));
    }

    /**
     * Validate incoming data for translation update.
     *
     * @since  1.0.0
     *
     * @param  array  $data  [
     *                          'locale' => "en",
     *                          'key' => "i18n:animals.dog",
     *                          'message' => "A dog barks."
     *                       ]
     *
     * @throws ValidationException
     */
    public function validateTranslationUpdate(array $data): mixed
    {
        request()['locale'] = $data['locale'];
        request()['key'] = $data['key'];
        request()['message'] = $data['message'];

        return app(config('i18n.validation.translation.update'));
    }
}
