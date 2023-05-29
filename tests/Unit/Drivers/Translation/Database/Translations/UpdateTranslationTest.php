<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationUpdated;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class UpdateTranslationTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('Hello', 'en', '');

        $this->assertIsBool($service->updateTranslation('Hello', 'en', 'salut'));
    }

    /** @test */
    public function it_throws_a_validation_exception()
    {
        $service = $this->app->make(Translation::class);

        $this->expectException(ValidationException::class);

        $service->updateTranslation('not existing', 'en', 'salut');
    }

    /** @test */
    public function it_dispatches_translation_updated_event_on_success()
    {
        Event::fake(TranslationUpdated::class);

        $service = $this->app->make(Translation::class);

        $service->setTranslation('Hello', 'en', 'Hello :Name');

        $service->updateTranslation('Hello', 'en', 'salut');

        Event::assertDispatched(function (TranslationUpdated $event) {
            return $event->locale === 'en' &&
                   $event->key === 'Hello' &&
                   $event->message_before === 'Hello :Name' &&
                   $event->message_after === 'salut';
        });
    }

    /** @test */
    public function it_updates_single_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('Hello', 'en', '');

        $service->updateTranslation('Hello', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['Hello'];

        $this->assertEquals('new value', $message);
    }

    /** @test */
    public function it_updates_group_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('messages.nested.message', 'en', '');

        $service->updateTranslation('messages.nested.message', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['messages.nested.message'];

        $this->assertEquals('new value', $message);
    }

    /** @test */
    public function it_updates_vendor_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('i18n::messages.nested.message', 'en', '');

        $service->updateTranslation('i18n::messages.nested.message', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['i18n::messages.nested.message'];

        $this->assertEquals('new value', $message);
    }
}
