<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Languages;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllLanguagesTest extends FileTestCase
{
    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $allLanguages = $service->allLanguages();

        $this->assertEquals([
            'de_CH' => 'Swiss German',
            'deu_DE' => 'German',
            'en' => 'English',
            'zho' => 'Chinese',
            'es' => 'es',
        ], $allLanguages);
    }

    /** @test */
    public function it_can_be_empty()
    {
        $service = $this->app->make(Translation::class);

        $this->emptyLangDirectory();

        $this->assertEmpty($service->allLanguages());

        $this->cleanFixtures();
    }

    /** @test */
    public function it_finds_languages_in_lang_subdirectories()
    {
        $service = $this->app->make(Translation::class);

        $this->emptyLangDirectory();

        mkdir($this->fixturesPath.'/lang/fr/', 0755, true);

        $this->assertEquals(['fr' => 'fr'], $service->allLanguages());

        $this->cleanFixtures();
    }

    /** @test */
    public function it_finds_languages_in_lang_json_files()
    {
        $service = $this->app->make(Translation::class);

        $this->emptyLangDirectory();

        $filepath = $this->fixturesPath.'/lang/fr.json';
        file_put_contents($filepath, '');

        $this->assertEquals(['fr' => 'fr'], $service->allLanguages());

        $this->cleanFixtures();
    }

    /** @test */
    public function it_finds_languages_in_vendor_lang_subdirectories()
    {
        $service = $this->app->make(Translation::class);

        $this->emptyLangDirectory();

        mkdir($this->fixturesPath.'/lang/vendor/test/fr/', 0755, true);

        $this->assertEquals(['fr' => 'fr'], $service->allLanguages());

        $this->cleanFixtures();
    }
}
