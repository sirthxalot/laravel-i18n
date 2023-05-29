<?php

namespace Sirthxalot\Laravel\I18n\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Contracts\Models\LanguageContract;
use Sirthxalot\Laravel\I18n\Contracts\Models\TranslationContract;
use Sirthxalot\Laravel\I18n\Drivers\Translation\Database;
use Sirthxalot\Laravel\I18n\Drivers\Translation\File;
use Sirthxalot\Laravel\I18n\Exceptions\Translation\File\TranslationNotFoundException;
use Sirthxalot\Laravel\I18n\Translation;
use Sirthxalot\Laravel\I18n\Translation\Scanner;

class SynchronizeTranslationDriversCommand extends Command
{
    /**
     * Available translation drivers.
     *
     * @var array
     */
    protected $drivers = ['file', 'database'];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'i18n:sync {driver?} {targetDriver?} {language?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize translation messages from one driver to another';

    /**
     * Create a new synchronize translation drivers command instance.
     *
     * @return void
     */
    public function __construct(
        protected readonly Scanner $scanner,
        protected readonly Translation $i18n,
        protected null|File|Database $driver = null,
        protected null|File|Database $targetDriver = null,
        protected array $driverLanguages = [],
        protected array $languages = [],
        protected null|LanguageContract $languageModel = null,
        protected null|TranslationContract $translationModel = null,
    ) {
        parent::__construct();
    }

    /**
     * Execute the synchronize translation drivers console command.
     *
     * @throws TranslationNotFoundException
     * @throws ValidationException
     */
    public function handle(): void
    {
        $driver = $this->mayAskForDriver();
        $this->validateDriver($driver);

        $targetDriver = $this->mayAskForTargetDriver();
        $this->validateDriver($targetDriver);

        $this->avoidDriverArgumentDuplications($driver, $targetDriver);

        $this->driver = $this->resolveDriver($driver);
        $this->targetDriver = $this->resolveDriver($targetDriver);

        $this->confirmEmptyLanguageArgument();
        $this->driverLanguages = $this->findOrFailLanguages();
        $this->validateLanguage();
        $this->languages = $this->getLanguagesFromArgument();

        $this->importTranslations();

        $this->newLine(2);

        $this->info(__('✅  Translation driver synchronization done.'));
    }

    /**
     * Give the user a choice of drivers if invalid or not set.
     */
    protected function mayAskForDriver(): bool|array|string|null
    {
        $driver = $this->argument('driver');

        if (! $driver || ! in_array($driver, $this->drivers)) {
            $driver = $this->choice(__('Which driver would you like to take translations from?'), $this->drivers);
        }

        return $driver;
    }

    /**
     * Give the user a choice of target drivers if invalid or not set.
     */
    protected function mayAskForTargetDriver(): bool|array|string|null
    {
        $driver = $this->argument('targetDriver');

        if (! $driver || ! in_array($driver, $this->drivers)) {
            $driver = $this->choice(__('Which driver would you like to add the translations to?'), $this->drivers);
        }

        return $driver;
    }

    /**
     * Check if the driver argument is valid.
     *
     * @param  string|null  $driver  "file" or "database"
     */
    protected function validateDriver(null|string $driver = null): void
    {
        if (! in_array($driver, $this->drivers)) {
            $allowed = Arr::join($this->drivers, ' , ', ' or ');

            $message = __('Could not resolve (:driver) translation driver.', compact('driver'));
            $message .= __('Allowed values are: :allowed.', compact('allowed'));

            $this->error($message);
            exit();
        }
    }

    /**
     * Check if driver is not the same as target driver.
     *
     * @param  string  $driver  "file"
     * @param  string  $targetDriver  "database"
     */
    protected function avoidDriverArgumentDuplications(string $driver, string $targetDriver): void
    {
        if ($driver === $targetDriver) {
            $this->error(__('The translation drivers are identical.'));
            exit();
        }
    }

    /**
     * Resolve the translation driver instances.
     */
    protected function resolveDriver(?string $driver = null): bool|File|Database
    {
        if ($driver === 'file') {
            return new File(new Filesystem, app('path.lang'), config('app.locale'), $this->scanner, config('i18n'));
        }

        return new Database(config('app.locale'), $this->scanner, config('i18n'), $this->languageModel, $this->translationModel);
    }

    /**
     * Confirm message if language argument is not set. In that
     * case we will use all languages found on the service.
     */
    protected function confirmEmptyLanguageArgument(): void
    {
        $language = $this->argument('language');

        if (! $language) {
            if (! $this->confirm(__('No language has been defined. Do you really want to synchronize all languages?'))) {
                exit();
            }
        }
    }

    /**
     * Get all languages found on the driver.
     */
    protected function findOrFailLanguages(): array
    {
        $languages = $this->driver->allLanguages();

        if (empty($languages)) {
            $this->warn(__('No languages available for this driver.'));
        }

        return $languages;
    }

    /**
     * Validate the language argument.
     */
    protected function validateLanguage(): void
    {
        $language = $this->argument('language');

        if (! empty($this->driverLanguages) && $language && ! $this->driver->languageExists($language)) {
            $message = __('The language (:language) does not exist.', compact('language'));
            $message .= __('Choose one of the following:');

            $this->choice($message, $this->driverLanguages);
        }
    }

    /**
     * Get all or single language depending on language argument.
     */
    protected function getLanguagesFromArgument(): array
    {
        $argument = $this->argument('language');

        if (! $argument) {
            return $this->driver->allLanguages();
        }

        return [$argument => $argument];
    }

    /**
     * Import translations from one driver into another.
     *
     * @throws ValidationException
     * @throws TranslationNotFoundException
     */
    protected function importTranslations(): void
    {
        $sourceTranslations = $this->driver->allTranslations();

        $bar = $this->output->createProgressBar(count($this->languages));

        $bar->start();

        $this->newLine();

        foreach ($this->languages as $locale => $name) {
            $translations = $sourceTranslations[$locale];

            if (count($translations) === 0) {
                $this->info(__('No translations found for :locale.', compact('locale')));
            } else {

                foreach ($translations as $tKey => $tMessage) {
                    $bool = $this->targetDriver->setTranslation($tKey, $locale, $tMessage);

                    if (true === $bool) {
                        $this->info(__('Translation message (:tKey) imported for :locale.', compact('locale', 'tKey')));
                    } else {
                        $this->warn(__('Could not import translation message (:tKey) for :locale.', compact('locale', 'tKey')));
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
    }
}
