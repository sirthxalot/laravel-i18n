<?php

namespace Sirthxalot\Laravel\I18n;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Sirthxalot\Laravel\I18n\Console\AddLanguageCommand;
use Sirthxalot\Laravel\I18n\Console\ImportMissingTranslationsCommand;
use Sirthxalot\Laravel\I18n\Console\ListLanguagesCommand;
use Sirthxalot\Laravel\I18n\Console\ListMissingTranslationsCommand;
use Sirthxalot\Laravel\I18n\Console\SetTranslationCommand;
use Sirthxalot\Laravel\I18n\Console\SynchronizeTranslationDriversCommand;
use Sirthxalot\Laravel\I18n\Contracts\Models\LanguageContract;
use Sirthxalot\Laravel\I18n\Contracts\Models\TranslationContract;
use Sirthxalot\Laravel\I18n\Translation\Manager as TranslationManager;
use Sirthxalot\Laravel\I18n\Translation\Scanner as TranslationScanner;

class I18nServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishFileGroups();
        $this->registerModelBindings();
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'i18n');

        if (! file_exists(database_path('/migrations/').'create_languages_table.php')) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations/create_languages_table.php');
        }

        if (! file_exists(database_path('/migrations/').'create_translations_table.php')) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations/create_translations_table.php');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/i18n.php', 'i18n');

        $this->registerHelpers();
        $this->registerTranslationScanner();
        $this->registerTranslationManager();
        $this->registerCommands();
    }

    /**
     * Publishing the packages file groups.
     *
     * @since  1.0.0
     */
    protected function publishFileGroups(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/i18n.php' => config_path('i18n.php'),
            ], 'i18n-config');

            $this->publishes([
                __DIR__.'/../lang/' => lang_path('/vendor/i18n/'),
            ], 'i18n-lang');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('/migrations/'),
            ], 'i18n-migration');
        }
    }

    /**
     * Register I18n artisan commands.
     *
     * @since  1.0.0
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AddLanguageCommand::class,
                ImportMissingTranslationsCommand::class,
                ListLanguagesCommand::class,
                ListMissingTranslationsCommand::class,
                SetTranslationCommand::class,
                SynchronizeTranslationDriversCommand::class,
            ]);
        }
    }

    /**
     * Register package helper functions.
     *
     * @since  1.0.0
     */
    protected function registerHelpers(): void
    {
        require_once __DIR__.'/helpers.php';
    }

    /**
     * Register the model bindings using contracts.
     *
     * @since  1.0.0
     */
    protected function registerModelBindings(): void
    {
        $this->app->bind(
            LanguageContract::class,
            fn ($app) => $app->make($app->config['i18n.database.models.language'])
        );

        $this->app->bind(
            TranslationContract::class,
            fn ($app) => $app->make($app->config['i18n.database.models.translation'])
        );
    }

    /**
     * Register the translation manager service.
     *
     * @since  1.0.0
     */
    protected function registerTranslationManager(): void
    {
        $this->app->bind(TranslationManager::class, function ($app) {
            return new TranslationManager(
                $app['path.lang'],
                $app['config']['i18n'],
                $app['config']['app'],
                $app->make(TranslationScanner::class),
                $app->make(LanguageContract::class),
                $app->make(TranslationContract::class),
            );
        });

        $this->app->singleton(Translation::class, function ($app) {
            return $app->make(TranslationManager::class)->resolve();
        });
    }

    /**
     * Register the translation scanner service.
     *
     * @since  1.0.0
     */
    protected function registerTranslationScanner(): void
    {
        $this->app->singleton(TranslationScanner::class, function () {
            return new TranslationScanner(
                (new Filesystem()),
                $this->app['config']['i18n'],
                $this->app['path.lang'],
            );
        });
    }
}
