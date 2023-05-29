<?php

namespace Sirthxalot\Laravel\I18n\Drivers\Translation\Traits\File;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Sirthxalot\Laravel\I18n\Exceptions\Translation\File\TranslationNotFoundException;
use SplFileInfo;

trait Translatable
{
    /**
     * Remove group translation from contents array and update file.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "animals.dog"
     * @param  string  $locale  "en_US"
     *
     * @throws TranslationNotFoundException
     */
    protected function deleteGroupTranslation(string $key, string $locale): bool
    {
        if ('group' === $this->translationKeyType($key)) {
            $filepath = $this->getTranslationFilepath($key, $locale);

            try {
                $contents = $this->disk->getRequire($filepath);
            } catch (Exception $e) {
                throw TranslationNotFoundException::create($filepath);
            }

            if (! is_array($contents)) {
                return false;
            }

            $arrayKey = Str::after($key, '.');

            $contents = Arr::dot($contents);

            unset($contents[$arrayKey]);

            return $this->putPhpTranslation($filepath, $contents);
        }

        return false;
    }

    /**
     * Remove single translation key from contents array and update file.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "Hello"
     * @param  string  $locale  "en_US"
     *
     * @throws TranslationNotFoundException
     */
    protected function deleteSingleTranslation(string $key, string $locale): bool
    {
        if ('single' === $this->translationKeyType($key)) {
            $filepath = $this->getTranslationFilepath($key, $locale);

            try {
                $contents = $this->disk->json($filepath);
            } catch (Exception $e) {
                throw TranslationNotFoundException::create($filepath);
            }

            if (! is_array($contents)) {
                return false;
            }

            unset($contents[$key]);

            return $this->putJsonTranslation($filepath, $contents);
        }

        return false;
    }

    /**
     * Remove vendor translation key from contents array and update file.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     *
     * @throws TranslationNotFoundException
     */
    protected function deleteVendorTranslation(string $key, string $locale): bool
    {
        if ('vendor' === $this->translationKeyType($key)) {
            $filepath = $this->getTranslationFilepath($key, $locale);

            try {
                $contents = $this->disk->getRequire($filepath);
            } catch (Exception $e) {
                throw TranslationNotFoundException::create($filepath);
            }

            if (! is_array($contents)) {
                return false;
            }

            $arrayKey = Str::after($key, '::');
            $arrayKey = Str::after($arrayKey, '.');

            $contents = Arr::dot($contents);

            unset($contents[$arrayKey]);

            return $this->putPhpTranslation($filepath, $contents);
        }

        return false;
    }

    /**
     * Generates the translation file if it does not exist.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     */
    protected function ensureTranslationExists(string $key, string $locale): void
    {
        $path = $this->getTranslationFilepath($key, $locale);

        if (
            ! $this->disk->exists($path) &&
            'single' === $this->translationKeyType($key)
        ) {
            $this->putJsonTranslation($path);
        }

        if (! $this->disk->exists($path) && (
            'group' === $this->translationKeyType($key) ||
            'vendor' === $this->translationKeyType($key)
        )) {
            $this->putPhpTranslation($path);
        }
    }

    /**
     * Get the absolute path to the translation file or empty string.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     */
    protected function getTranslationFilepath(string $key, string $locale): string
    {
        $languageDirectory = $this->langDirectory.DIRECTORY_SEPARATOR;

        if ('single' === $this->translationKeyType($key)) {
            return $languageDirectory.$locale.'.json';
        }

        if ('group' === $this->translationKeyType($key)) {
            $path = $languageDirectory;
            $path .= $locale.DIRECTORY_SEPARATOR;
            $path .= Str::before($key, '.');
            $path .= '.php';

            return $path;
        }

        if ('vendor' === $this->translationKeyType($key)) {
            $filename = Str::after($key, '::');
            $filename = Str::before($filename, '.');

            $path = $languageDirectory;
            $path .= 'vendor'.DIRECTORY_SEPARATOR;
            $path .= Str::before($key, '::').DIRECTORY_SEPARATOR;
            $path .= $locale.DIRECTORY_SEPARATOR;
            $path .= $filename;
            $path .= '.php';

            return $path;
        }

        return '';
    }

    /**
     * Write translations into single json file.
     *
     * @since  1.0.0
     *
     * @param  string  $path  Absolute path to the file.
     * @param  array|null  $contents  Translation content.
     */
    protected function putJsonTranslation(string $path, ?array $contents = []): bool
    {
        $this->disk->ensureDirectoryExists(dirname($path));

        if (empty($contents)) {
            return $this->disk->put($path, '{}');
        }

        $contents = json_encode($contents, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return $this->disk->put($path, $contents);
    }

    /**
     * Write translations into a php translation file.
     *
     * @since  1.0.0
     *
     * @param  string  $path  Absolute path to the file.
     * @param  array|null  $contents  Translation content.
     */
    protected function putPhpTranslation(string $path, ?array $contents = []): bool
    {
        $this->disk->ensureDirectoryExists(dirname($path));

        $contents = Arr::undot($contents);

        $contents = "<?php\n\nreturn ".var_export_with_array_squares($contents, true).';'.\PHP_EOL;

        return $this->disk->put($path, $contents);
    }

    /**
     * Get the contents of a single translation file (.json).
     *
     * @since  1.0.0
     *
     * @return array  ['en' => ['Hello' => "Hello :Name"]
     */
    protected function readJsonTranslationFile(SplFileInfo $file): array
    {
        $locale = str_replace('.json', '', basename($file));

        $content = json_decode($file->getContents(), true);

        if (! is_array($content)) {
            $content = [];
        }

        return [$locale => Arr::dot($content)];
    }

    /**
     * Get the contents of a group translation file (.php).
     *
     * Ensure that all translation files don't have any malicious
     * code. All translation files will be required and executed
     * which can lead into a security issue.
     *
     * @since  1.0.0
     *
     * @param  string  $groupName  "animals.dog"
     * @param  string  $locale  "en_US"
     * @param  string|null  $groupPrefix  "i18n::"
     * @return array  ['en' => ['i18n::animals.dog' => "A dog barks."]
     *
     * @throws TranslationNotFoundException
     */
    protected function readPhpTranslationFile(
        SplFileInfo $file,
        string $groupName,
        string $locale,
        ?string $groupPrefix = null
    ): array {
        try {
            $content = $this->disk->getRequire($file->getRealPath());
        } catch (Exception $e) {
            throw TranslationNotFoundException::create($file->getRealPath());
        }

        if (! is_array($content)) {
            return [];
        }

        $content = Collection::make($content);

        /**
         * Filter out empty arrays only from the value. We want
         * to avoid that empty strings are also being filtered
         * this is why we are doing this.
         */
        $content = $content->filter(function ($value) {
            if (is_array($value)) {
                return ! empty($value);
            }

            return true;
        });

        $content = Arr::dot($content, $groupPrefix.$groupName.'.');

        return [$locale => $content];
    }

    /**
     * Get the contents of all group translations found.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => ['animals.dog' => "A dog barks."]
     *
     * @throws TranslationNotFoundException
     */
    protected function scanGroupTranslations(): array
    {
        $directory = $this->langDirectory;

        if (! $this->disk->exists($directory)) {
            return [];
        }

        $directories = $this->disk->directories($directory);
        $directories = Collection::make($directories);

        $directories = $directories->filter(function ($path) {
            return basename($path) !== 'vendor';
        });

        // get all translation file contents for given path
        $files = $directories->mapWithKeys(function ($path) use ($directory) {
            $files = Collection::make($this->disk->allFiles($path));

            $files = $files->filter(function ($file) {
                return 'php' === $file->getExtension();
            });

            // map translation file content to groups
            return $files->mapToGroups(function ($file) use ($directory) {
                $groupName = Str::replace('.'.$file->getExtension(), '', $file->getFilename());
                $locale = Str::replace($directory, '', $file->getPath());
                $locale = ltrim($locale, DIRECTORY_SEPARATOR);

                return $this->readPhpTranslationFile($file, $groupName, $locale);
            });
        });

        return $files->mapWithKeys(function ($collection, $locale) {
            return [$locale => $collection->collapse()];
        })->toArray();
    }

    /**
     * Get the contents of all single translations found.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => ['Hello' => "Hello :Name"]
     */
    protected function scanSingleTranslations(): array
    {
        $directory = $this->langDirectory;

        if (! $this->disk->exists($directory)) {
            return [];
        }

        $files = $this->disk->allFiles($directory);
        $files = Collection::make($files);

        $files = $files->filter(function ($file) {
            return $file->getExtension() === 'json';
        });

        if ($files->isEmpty()) {
            return [];
        }

        return $files->mapWithKeys(function ($file) {
            return $this->readJsonTranslationFile($file);
        })->toArray();
    }

    /**
     * Get the contents of all translations found.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => ['i18n::animals.dog' => "A dog barks."]
     *
     * @throws TranslationNotFoundException
     */
    protected function scanTranslations(): array
    {
        // get all translations
        $singles = $this->scanSingleTranslations();
        $groups = $this->scanGroupTranslations();
        $vendors = $this->scanVendorTranslations();

        $allTranslations = array_merge_recursive($singles, $groups, $vendors);
        $allTranslations = Collection::make($allTranslations);

        $allTranslations = $allTranslations->sortKeys();

        // sort the translation keys just for cosmetic
        $allTranslations = $allTranslations->mapWithKeys(function ($array, $locale) {
            $collection = Collection::make($array);
            $collection = $collection->sortKeys();

            return [$locale => $collection->toArray()];
        })->toArray();

        return $allTranslations;
    }

    /**
     * Get the contents of all vendor translations found.
     *
     * @since  1.0.0
     *
     * @return array  ['en' => ['i18n::animals.dog' => "A dog barks."]
     */
    protected function scanVendorTranslations(): array
    {
        $directory = $this->langDirectory.'/vendor/';

        if (! $this->disk->exists($directory)) {
            return [];
        }

        $files = $this->disk->allFiles($directory.'*/');
        $files = Collection::make($files);

        $files = $files->filter(function ($file) {
            return 'php' === $file->getExtension();
        });

        if ($files->isEmpty()) {
            return [];
        }

        $files = $files->mapToGroups(function ($file) use ($directory) {
            $locale = $file->getRelativePath();
            $relativePath = Str::replace($directory, '', $file->getPath());
            $namespace = Str::before($relativePath, DIRECTORY_SEPARATOR);
            $groupName = Str::replace('.'.$file->getExtension(), '', $file->getFilename());

            return $this->readPhpTranslationFile($file, $groupName, $locale, $namespace.'::');
        });

        return $files->mapWithKeys(function ($collection, $locale) {
            return [$locale => $collection->collapse()];
        })->toArray();
    }

    /**
     * Update or create group translation.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "animals.dog"
     * @param  string  $locale  "en_US"
     * @param  string  $message  "A dog barks."
     *
     * @throws TranslationNotFoundException
     */
    protected function updateOrCreateGroupTranslation(string $key, string $locale, string $message = ''): bool
    {
        if ('group' === $this->translationKeyType($key)) {
            $path = $this->getTranslationFilepath($key, $locale);

            $this->disk->ensureDirectoryExists(dirname($path));

            if (! $this->disk->exists($path)) {
                $contents = [];
            } else {
                try {
                    $contents = $this->disk->getRequire($path);
                } catch (Exception $e) {
                    throw TranslationNotFoundException::create($path);
                }
            }

            if (! is_array($contents)) {
                return false;
            }

            $arrayKey = Str::after($key, '.');

            $contents = Arr::dot($contents);
            $contents[$arrayKey] = $message;

            return $this->putPhpTranslation($path, $contents);
        }

        return false;
    }

    /**
     * Update or create single translation.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "Hello"
     * @param  string  $locale  "en_US"
     * @param  string  $message  "Hello :Dave"
     *
     * @throws TranslationNotFoundException
     */
    protected function updateOrCreateSingleTranslation(string $key, string $locale, string $message = ''): bool
    {
        if ('single' === $this->translationKeyType($key)) {
            $path = $this->getTranslationFilepath($key, $locale);

            $this->disk->ensureDirectoryExists(dirname($path));

            if (! $this->disk->exists($path)) {
                $contents = [];
            } else {
                try {
                    $contents = $this->disk->json($path);
                } catch (Exception $e) {
                    throw TranslationNotFoundException::create($path);
                }
            }

            if (! is_array($contents)) {
                return false;
            }

            $contents[$key] = $message;

            return $this->putJsonTranslation($path, $contents);
        }

        return false;
    }

    /**
     * Update or create vendor translation.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     * @param  string  $message  "A dog barks."
     *
     * @throws TranslationNotFoundException
     */
    protected function updateOrCreateVendorTranslation(string $key, string $locale, string $message = ''): bool
    {
        if ('vendor' === $this->translationKeyType($key)) {
            $path = $this->getTranslationFilepath($key, $locale);

            $this->disk->ensureDirectoryExists(dirname($path));

            if (! $this->disk->exists($path)) {
                $contents = [];
            } else {
                try {
                    $contents = $this->disk->getRequire($path);
                } catch (Exception $e) {
                    throw TranslationNotFoundException::create($path);
                }
            }

            if (! is_array($contents)) {
                return false;
            }

            $arrayKey = Str::after($key, '::');
            $arrayKey = Str::after($arrayKey, '.');

            $contents = Arr::dot($contents);
            $contents[$arrayKey] = $message;

            return $this->putPhpTranslation($path, $contents);
        }

        return false;
    }
}
