<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageCreated;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationCreated;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationUpdated;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class SetTranslationTest extends FileTestCase
{
    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsBool($service->setTranslation('New Key', 'en', 'New Value'));

        $this->cleanFixtures();
    }

    /** @test */
    public function it_throws_a_validation_exception()
    {
        $service = $this->app->make(Translation::class);

        $this->expectException(ValidationException::class);

        $service->setTranslation('', '', '');

        $this->cleanFixtures();
    }

    /** @test */
    public function it_dispatches_translation_created_event_on_success()
    {
        Event::fake(TranslationCreated::class);

        $service = $this->app->make(Translation::class);

        $service->setTranslation('New Key', 'en', 'New Value');

        Event::assertDispatched(function (TranslationCreated $event) {
            return $event->locale === 'en' &&
                   $event->key === 'New Key' &&
                   $event->message === 'New Value';
        });

        $this->cleanFixtures();
    }

    /** @test */
    public function it_dispatches_translation_updated_event_on_success()
    {
        Event::fake(TranslationUpdated::class);

        $service = $this->app->make(Translation::class);

        $service->setTranslation('Hello', 'en', 'salut');

        Event::assertDispatched(function (TranslationUpdated $event) {
            return $event->locale === 'en' &&
                $event->key === 'Hello' &&
                $event->message_before === 'Hello :Name' &&
                $event->message_after === 'salut';
        });

        $this->cleanFixtures();
    }

    /** @test */
    public function it_dispatches_language_created_event_on_success()
    {
        Event::fake(LanguageCreated::class);

        $service = $this->app->make(Translation::class);

        $service->setTranslation('Hello', 'foo', 'salut');

        Event::assertDispatched(function (LanguageCreated $event) {
            return $event->locale === 'foo';
        });

        $this->cleanFixtures();
    }
}
