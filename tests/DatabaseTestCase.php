<?php

namespace Sirthxalot\Laravel\I18n\Tests;

use Orchestra\Testbench\TestCase as Testbench;
use Sirthxalot\Laravel\I18n\I18nServiceProvider;
use Sirthxalot\Laravel\I18n\TranslationServiceProvider;

class DatabaseTestCase extends Testbench
{
    protected string $fixturesPath = '';

    protected string $langPath = '';

    protected string $translationsPath = '';

    /**
     * Create a new file driver test case instance.
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->fixturesPath = realpath(__DIR__.'/fixtures/');
        $this->langPath = realpath($this->fixturesPath.'/lang/');
        $this->translationsPath = realpath($this->fixturesPath.'/scan/');
    }

    /**
     * Define test environment setup.
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('i18n.driver', 'database');
        $app['config']->set('i18n.database.connection', 'testing');
        $app['config']->set('i18n.scan_paths', [$this->translationsPath]);
        $app['config']->set('path.lang', $this->langPath);

        $app['path.lang'] = $app['config']->get('path.lang');
    }

    /**
     * Load package service providers.
     *
     * @return string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            I18nServiceProvider::class,
            TranslationServiceProvider::class,
        ];
    }
}
