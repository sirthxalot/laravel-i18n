<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationUpdated;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class UpdateTranslationTest extends FileTestCase
{
    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsBool($service->updateTranslation('Hello', 'en', 'salut'));

        $this->cleanFixtures();
    }

    /** @test */
    public function it_throws_a_validation_exception()
    {
        $service = $this->app->make(Translation::class);

        $this->expectException(ValidationException::class);

        $service->updateTranslation('not existing', 'en', 'salut');

        $this->cleanFixtures();
    }

    /** @test */
    public function it_dispatches_translation_updated_event_on_success()
    {
        Event::fake(TranslationUpdated::class);

        $service = $this->app->make(Translation::class);

        $service->updateTranslation('Hello', 'en', 'salut');

        Event::assertDispatched(function (TranslationUpdated $event) {
            return $event->locale === 'en' &&
                   $event->key === 'Hello' &&
                   $event->message_before === 'Hello :Name' &&
                   $event->message_after === 'salut';
        });

        $this->cleanFixtures();
    }

    /** @test */
    public function it_updates_single_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->updateTranslation('Hello', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['Hello'];

        $this->assertEquals('new value', $message);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_updates_group_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->updateTranslation('messages.nested.message', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['messages.nested.message'];

        $this->assertEquals('new value', $message);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_updates_vendor_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->updateTranslation('i18n::messages.nested.message', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['i18n::messages.nested.message'];

        $this->assertEquals('new value', $message);

        $this->cleanFixtures();
    }
}
