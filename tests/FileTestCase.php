<?php

namespace Sirthxalot\Laravel\I18n\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase as Testbench;
use Sirthxalot\Laravel\I18n\I18nServiceProvider;
use Sirthxalot\Laravel\I18n\TranslationServiceProvider;

class FileTestCase extends Testbench
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
        $app['config']->set('i18n.driver', 'file');
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

    /**
     * Recursively delete language directories and files.
     */
    protected function emptyLangDirectory(): void
    {
        $filesystem = (new Filesystem());
        $filesystem->deleteDirectory($this->langPath);
        $filesystem->makeDirectory($this->langPath, 0755, true);
    }

    /**
     * Reset the fixtures file structure.
     */
    protected function cleanFixtures(): void
    {
        $filesystem = (new Filesystem());
        $filesystem->deleteDirectory($this->langPath);
        $filesystem->ensureDirectoryExists($this->langPath);
        $filesystem->makeDirectory($this->langPath.'/de_CH/', 0755, true);
        $filesystem->makeDirectory($this->langPath.'/deu_DE/', 0755, true);
        $filesystem->makeDirectory($this->langPath.'/en/', 0755, true);
        $filesystem->makeDirectory($this->langPath.'/zho/', 0755, true);
        $filesystem->makeDirectory($this->langPath.'/vendor/i18n/en/', 0755, true);
        $filesystem->makeDirectory($this->langPath.'/vendor/i18n/es/', 0755, true);

        $this->createSingleLangFiles();
        $this->createGroupLangFiles();
        $this->createVendorLangFiles();
    }

    /**
     * Create fixtures single translation files.
     */
    protected function createSingleLangFiles(): void
    {
        file_put_contents(
            $this->langPath.'/de_CH.json',
            json_encode((object) [
                'Hello' => 'Höi :Name',
                'There is one apple|There are many apples' => 'Es hät en öpfel|Es git vieli öpfeli mindestens :count',
                '{0} There are none|[1,19] There are some|[20,*] There are many' => '{0} Es hät kei|[1,19] Es hät es paar|[20,*] Uii sind das viil',
                '{1} :value minute ago|[2,*] :value minutes ago' => '{1} vonere :value minute|[2,*] vor :value minütlis',
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        file_put_contents(
            $this->langPath.'/deu_DE.json',
            json_encode((object) [
                'Hello' => 'Hallo :Name',
                'There is one apple|There are many apples' => 'Ein Apfel|Es gibt viele Äpfel mindestens :count',
                '{0} There are none|[1,19] There are some|[20,*] There are many' => '{0} Es gibt keine|[1,19] Es gibt ein paar|[20,*] Es gibt sehr viele',
                '{1} :value minute ago|[2,*] :value minutes ago' => '{1} vor :value Minute|[2,*] vor :value Minuten',
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        file_put_contents(
            $this->langPath.'/en.json',
            json_encode((object) [
                'Hello' => 'Hello :Name',
                'There is one apple|There are many apples' => 'There is one apple|There are many apples at least :count',
                '{0} There are none|[1,19] There are some|[20,*] There are many' => '{0} There are none|[1,19] There are some|[20,*] There are many',
                '{1} :value minute ago|[2,*] :value minutes ago' => '{1} :value minute ago|[2,*] :value minutes ago',
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );

        file_put_contents(
            $this->langPath.'/zho.json',
            json_encode((object) [
                'Hello' => '你好 :Name',
                'There is one apple|There are many apples' => '有一個蘋果|有很多蘋果 :count',
                '{0} There are none|[1,19] There are some|[20,*] There are many' => '{0} 沒有了|[1,19] 有一些|[20,*] 有許多',
                '{1} :value minute ago|[2,*] :value minutes ago' => '{1} 一分鐘前|[2,*] :value 幾分鐘前',
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    /**
     * Create fixtures group translation files.
     */
    protected function createGroupLangFiles(): void
    {
        file_put_contents(
            $this->langPath.'/de_CH/pluralization.php',
            "<?php\n\nreturn ".var_export([
                'ago' => '{1} vonere :value minute|[2,*] vor :value minütlis',
                'apples' => 'Es hät en öpfel|Es git vieli öpfeli mindestens :count',
                'bananas' => '{0} Es hät kei|[1,19] Es hät es paar|[20,*] Uii sind das viil',
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $this->langPath.'/de_CH/messages.php',
            "<?php\n\nreturn ".var_export([
                'nested' => [
                    'message' => 'verschachtetli nachricht',
                    'welcome' => 'Hoi :NAME',
                ],
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $this->langPath.'/deu_DE/pluralization.php',
            "<?php\n\nreturn ".var_export([
                'ago' => '{1} vor :value Minute|[2,*] vor :value Minuten',
                'apples' => 'Ein Apfel|Es gibt viele Äpfel mindestens :count',
                'bananas' => '{0} Es gibt keine|[1,19] Es gibt ein paar|[20,*] Es gibt sehr viele',
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $this->langPath.'/deu_DE/messages.php',
            "<?php\n\nreturn ".var_export([
                'nested' => [
                    'message' => 'verschachtelte Nachricht',
                    'welcome' => 'Hallo :NAME',
                ],
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $this->langPath.'/en/pluralization.php',
            "<?php\n\nreturn ".var_export([
                'ago' => '{1} :value minute ago|[2,*] :value minutes ago',
                'apples' => 'There is one apple|There are many apples at least :count',
                'bananas' => '{0} There are none|[1,19] There are some|[20,*] There are many',
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $this->langPath.'/en/messages.php',
            "<?php\n\nreturn ".var_export([
                'empty' => '',
                'only' => 'j',
                'nested' => [
                    'message' => 'nested message',
                    'welcome' => 'Hello :NAME',
                ],
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $this->langPath.'/zho/pluralization.php',
            "<?php\n\nreturn ".var_export([
                'ago' => '{1} 一分鐘前|[2,*] :value 幾分鐘前',
                'apples' => '有一個蘋果|有很多蘋果 :count',
                'bananas' => '{0} 沒有了|[1,19] 有一些|[20,*] 有許多',
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $this->langPath.'/zho/messages.php',
            "<?php\n\nreturn ".var_export([
                'nested' => [
                    'message' => '嵌套消息',
                    'welcome' => '你好 :NAME',
                ],
            ], true).';'.\PHP_EOL
        );
    }

    /**
     * Create fixtures vendor translation files.
     */
    protected function createVendorLangFiles(): void
    {
        $vendorPath = $this->langPath.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'i18n';

        file_put_contents(
            $vendorPath.'/en/languages.php',
            "<?php\n\nreturn ".var_export([
                'de_CH' => 'Swiss German',
                'deu_DE' => 'German',
                'en' => 'English',
                'es' => 'es',
                'zho' => 'Chinese',
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $vendorPath.'/en/pluralization.php',
            "<?php\n\nreturn ".var_export([
                'ago' => '{1} :value minute ago|[2,*] :value minutes ago',
                'apples' => 'There is one apple|There are many apples at least :count',
                'bananas' => '{0} There are none|[1,19] There are some|[20,*] There are many',
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $vendorPath.'/en/messages.php',
            "<?php\n\nreturn ".var_export([
                'nested' => [
                    'message' => 'nested message',
                    'welcome' => 'Hello :NAME',
                ],
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $vendorPath.'/es/languages.php',
            "<?php\n\nreturn ".var_export([
                'de_CH' => 'Alemana suiza',
                'deu_DE' => 'Alemana',
                'en' => 'Inglesa',
                'es' => 'es',
                'zho' => 'China',
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $vendorPath.'/es/pluralization.php',
            "<?php\n\nreturn ".var_export([
                'ago' => '{1} Hase :value minuto|[2,*] Hase :value minutos',
                'apples' => 'Hay una manzana|Hay muchas manzanas por lo menos :count',
                'bananas' => '{0} No hay ninguno|[1,19] Hay algunos|[20,*] Hay muchos',
            ], true).';'.\PHP_EOL
        );

        file_put_contents(
            $vendorPath.'/es/messages.php',
            "<?php\n\nreturn ".var_export([
                'nested' => [
                    'message' => '',
                    'welcome' => 'Hola :NAME',
                ],
            ], true).';'.\PHP_EOL
        );
    }
}
