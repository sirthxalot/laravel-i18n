<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Languages;

use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageCreated;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AddLanguageTest extends FileTestCase
{
    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsBool($service->addLanguage('fr_CH'));

        $this->cleanFixtures();
    }

    /** @test */
    public function it_throws_validation_exception_if_invalid()
    {
        $service = $this->app->make(Translation::class);

        $this->expectException(ValidationException::class);

        $bool = $service->addLanguage('a');
    }

    /** @test */
    public function it_dispatches_language_created_event_on_success()
    {
        Event::fake(LanguageCreated::class);

        $service = $this->app->make(Translation::class);

        $service->addLanguage('foo_BA');

        Event::assertDispatched(function (LanguageCreated $event) {
            return $event->locale === 'foo_BA';
        });

        $this->cleanFixtures();
    }

    /** @test */
    public function its_available_in_list()
    {
        $service = $this->app->make(Translation::class);

        $this->assertArrayNotHasKey('nl', $service->allLanguages());

        $bool = $service->addLanguage('nl');

        $this->assertArrayHasKey('nl', $service->allLanguages());

        $this->cleanFixtures();
    }

    /** @test */
    public function it_generates_language_directory()
    {
        $service = $this->app->make(Translation::class);

        $this->emptyLangDirectory();

        $path = $this->fixturesPath.'/lang/de/';

        $this->assertDirectoryDoesNotExist($path);

        $service->addLanguage('de');

        $this->assertDirectoryExists($path);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_generates_language_file()
    {
        $service = $this->app->make(Translation::class);

        $this->emptyLangDirectory();

        $path = $this->fixturesPath.'/lang/de.json';

        $this->assertFileDoesNotExist($path);

        $service->addLanguage('de');

        $this->assertFileExists($path);

        $this->cleanFixtures();
    }
}
