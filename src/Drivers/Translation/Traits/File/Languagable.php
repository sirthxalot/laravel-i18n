<?php

namespace Sirthxalot\Laravel\I18n\Drivers\Translation\Traits\File;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait Languagable
{
    /**
     * Recursively delete a language directory for a given locale.
     *
     * @since  1.0.0
     *
     * @param  string  $locale  "en_US"
     */
    protected function deleteLangDirectoryFor(string $locale): bool
    {
        $path = $this->getLanguageDirectoryPath($locale);

        return $this->disk->deleteDirectory($path);
    }

    /**
     * Delete the language json file for a given locale.
     *
     * @since  1.0.0
     *
     * @param  string  $locale  "en_US"
     */
    protected function deleteLangFileFor(string $locale): bool
    {
        $path = $this->getLanguageFilepath($locale);

        return $this->disk->delete($path);
    }

    /**
     * Recursively delete vendor language directory for given locale.
     *
     * @since  1.0.0
     *
     * @param  string  $locale  "en_US"
     */
    protected function deleteVendorLangDirectoryFor(string $locale): bool
    {
        $path = $this->getLanguageDirectoryPath('vendor');

        if (! $this->disk->exists($path)) {
            return false;
        }

        $vendorDirectories = $this->disk->directories($path.'/*/');
        $vendorDirectories = Collection::make($vendorDirectories);

        $vendorDirectories = $vendorDirectories->filter(function ($language) use ($locale) {
            return basename($language) == $locale;
        });

        $vendorDirectories->each(function ($path) {
            $this->disk->deleteDirectory($path);
        });

        return ! $this->disk->exists($path);
    }

    /**
     * Delete the vendor language json file for a given locale.
     *
     * @since  1.0.0
     *
     * @param  string  $locale  "en_US"
     */
    protected function deleteVendorLangFileFor(string $locale): bool
    {
        $path = $this->getLanguageDirectoryPath('vendor');

        if (! $this->disk->exists($path)) {
            return false;
        }

        $vendorFiles = $this->disk->files($path.'/*/');
        $vendorFiles = Collection::make($vendorFiles);

        $vendorFiles = $vendorFiles->filter(function ($language) use ($locale) {
            return basename($language) == $locale.'.json';
        });

        $vendorFiles->each(function ($file) {
            $this->disk->delete($file->getRealPath());
        });

        return ! $this->disk->exists($path);
    }

    /**
     * Generates the language directory and json file for a given locale.
     *
     * @since  1.0.0
     *
     * @param  string  $locale  "en_US"
     */
    protected function ensureLanguageExists(string $locale): void
    {
        $directoryPath = $this->getLanguageDirectoryPath($locale);

        $this->disk->ensureDirectoryExists($directoryPath);

        $filePath = $this->getLanguageFilepath($locale);

        $this->putJsonTranslation($filePath);
    }

    /**
     * Get the absolute path to the language directory.
     *
     * @since  1.0.0
     *
     * @param  false|string  $locale  "en_US"
     */
    protected function getLanguageDirectoryPath(false|string $locale = false): string
    {
        $path = $this->langDirectory;

        if (! $locale) {
            return $path;
        }

        $path .= DIRECTORY_SEPARATOR;
        $path .= $locale;
        $path .= DIRECTORY_SEPARATOR;

        return $path;
    }

    /**
     * Get the absolute path to the language json file.
     *
     * @since  1.0.0
     *
     * @param  string  $locale  "en_US"
     */
    protected function getLanguageFilepath(string $locale): string
    {
        $path = $this->langDirectory;
        $path .= DIRECTORY_SEPARATOR;
        $path .= $locale;
        $path .= '.json';

        return $path;
    }

    /**
     * Get all languages found within the language directories.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => "English"]
     */
    protected function scanLangDirectoryForLanguages(): array
    {
        $path = $this->getLanguageDirectoryPath();

        if (! $this->disk->exists($path)) {
            return [];
        }

        $directories = $this->disk->directories($this->langDirectory);
        $directories = Collection::make($directories);

        $directories = $directories->mapWithKeys(function ($path) {
            return [basename($path) => i18n_lang(basename($path))];
        });

        $directories = $directories->filter(function ($language) {
            return 'vendor' !== $language;
        });

        return $directories->toArray();
    }

    /**
     * Get all languages found within the language json files.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => "English"]
     */
    protected function scanLangFilesForLanguage(): array
    {
        $path = $this->getLanguageDirectoryPath();

        if (! $this->disk->exists($path)) {
            return [];
        }

        $files = $this->disk->allFiles($path);
        $files = Collection::make($files);

        $files = $files->filter(function ($file) {
            return 'json' === $file->getExtension();
        });

        $files = $files->mapWithKeys(function ($file) {
            $search = '.'.$file->getExtension();
            $filename = $file->getFilename();

            $locale = Str::replace($search, '', $filename);

            return [$locale => i18n_lang($locale)];
        });

        return $files->toArray();
    }

    /**
     * Get all languages found within the vendor lang directories.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => "English"]
     */
    protected function scanVendorLangDirectoriesForLanguages(): array
    {
        $path = realpath($this->getLanguageDirectoryPath('vendor'));

        if (! $this->disk->exists($path)) {
            return [];
        }

        $scan = $path.DIRECTORY_SEPARATOR.'*'.DIRECTORY_SEPARATOR;

        $directories = $this->disk->directories($scan);
        $directories = Collection::make($directories);

        $directories = $directories->mapWithKeys(function ($path) {
            return [basename($path) => i18n_lang(basename($path))];
        });

        return $directories->toArray();
    }
}
