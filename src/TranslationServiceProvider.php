<?php

namespace Sirthxalot\Laravel\I18n;

use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;
use Illuminate\Translation\Translator;
use Sirthxalot\Laravel\I18n\Translation\Database\Loader as DatabaseLoader;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register package bindings in the container.
     *
     * @since  1.0.0
     */
    public function register(): void
    {
        if ($this->app['config']['i18n.driver'] === 'database') {
            $this->registerDatabaseLoader();
            $this->registerDatabaseTranslator();
        } else {
            parent::register();
        }
    }

    /**
     * Register database translation loader service.
     *
     * @since  1.0.0
     */
    protected function registerDatabaseLoader(): void
    {
        $this->app->singleton('translation.loader', function ($app) {
            return new DatabaseLoader($app->make(Translation::class));
        });
    }

    /**
     * Register database translator service.
     *
     * @since  1.0.0
     */
    protected function registerDatabaseTranslator(): void
    {
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];

            /**
             * When registering the translator component,
             * we'll need to set the default locale as well as
             * the fallback locale. So, we'll grab the application
             * configuration, so we can easily get both of these
             * values from there.
             */
            $locale = $app['config']['app.locale'];

            $trans = new Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });
    }
}
