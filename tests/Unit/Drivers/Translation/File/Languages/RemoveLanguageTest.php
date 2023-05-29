<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Languages;

use Illuminate\Support\Facades\Event;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageDeleted;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class RemoveLanguageTest extends FileTestCase
{
    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->removeLanguage('');
        $this->assertIsBool($bool);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_can_be_false()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->removeLanguage('');
        $this->assertFalse($bool);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_can_be_true()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->removeLanguage('deu_DE');
        $this->assertTrue($bool);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_dispatches_language_deleted_event_on_success()
    {
        Event::fake(LanguageDeleted::class);

        $service = $this->app->make(Translation::class);

        $service->removeLanguage('zho');

        Event::assertDispatched(function (LanguageDeleted $event) {
            return $event->locale === 'zho';
        });

        $this->cleanFixtures();
    }

    /** @test */
    public function it_removes_the_single_translation_file()
    {
        $service = $this->app->make(Translation::class);

        $this->assertFileExists($this->langPath.'/deu_DE.json');

        $service->removeLanguage('deu_DE');

        $this->assertFileDoesNotExist($this->langPath.'/deu_DE.json');

        $this->cleanFixtures();
    }

    /** @test */
    public function it_removes_the_lang_directory()
    {
        $service = $this->app->make(Translation::class);

        $this->assertDirectoryExists($this->langPath.'/deu_DE');

        $service->removeLanguage('deu_DE');

        $this->assertDirectoryDoesNotExist($this->langPath.'/deu_DE');

        $this->cleanFixtures();
    }

    /** @test */
    public function it_removes_the_vendor_lang_directory()
    {
        $service = $this->app->make(Translation::class);

        $this->assertDirectoryExists($this->langPath.'/vendor/i18n/es/');

        $service->removeLanguage('es');

        $this->assertDirectoryDoesNotExist($this->langPath.'/vendor/i18n/es/');

        $this->cleanFixtures();
    }

    /** @test */
    public function it_removes_it_from_all_languages()
    {
        $service = $this->app->make(Translation::class);

        $allLanguages = $service->allLanguages();
        $this->assertArrayHasKey('es', $allLanguages);

        $service->removeLanguage('es');

        $allLanguages = $service->allLanguages();
        $this->assertArrayNotHasKey('es', $allLanguages);

        $this->cleanFixtures();
    }
}
