<?php

namespace Sirthxalot\Laravel\I18n\Translation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Sirthxalot\Laravel\I18n\Contracts\Models\LanguageContract;
use Sirthxalot\Laravel\I18n\Contracts\Models\TranslationContract;
use Sirthxalot\Laravel\I18n\Drivers\Translation\Database;
use Sirthxalot\Laravel\I18n\Drivers\Translation\File;
use Sirthxalot\Laravel\I18n\Exceptions\Translation\DriverNotFoundException;

class Manager
{
    /**
     * Create a new translation manager instance.
     */
    public function __construct(
        public readonly string $appLanguagePath,
        public readonly array $i18nConfig,
        public readonly array $appConfig,
        public readonly Scanner $scanner,
        public LanguageContract $languageModel,
        public TranslationContract $translationModel,
    ) {
        // ...
    }

    /**
     * Resolve the translation driver.
     *
     * @since  1.0.0
     */
    public function resolve(): Database|File
    {
        $driver = $this->i18nConfig['driver'];
        $driverResolver = Str::studly($driver);
        $method = "resolve{$driverResolver}Driver";

        if (! method_exists($this, $method)) {
            throw DriverNotFoundException::create($driver);
        }

        return $this->{$method}();
    }

    /**
     * Resolve the database translation driver.
     *
     * @since  1.0.0
     */
    protected function resolveDatabaseDriver(): Database
    {
        return new Database(
            $this->appConfig['locale'],
            $this->scanner,
            $this->i18nConfig,
            $this->languageModel,
            $this->translationModel
        );
    }

    /**
     * Resolve the filesystem translation driver.
     *
     * @since  1.0.0
     */
    protected function resolveFileDriver(): File
    {
        return new File(
            new Filesystem,
            $this->appLanguagePath,
            $this->appConfig['locale'],
            $this->scanner,
            $this->i18nConfig
        );
    }
}
