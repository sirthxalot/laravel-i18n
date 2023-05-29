<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationCreated;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AddTranslationTest extends DatabaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Language::factory()->create(['locale' => 'en']);
    }

    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->addTranslation('New Key', 'en', 'New Value');

        $this->assertIsBool($bool);
    }

    /** @test */
    public function it_throws_a_validation_exception()
    {
        $service = $this->app->make(Translation::class);

        $this->expectException(ValidationException::class);

        $service->addTranslation('', '', '');
    }

    /** @test */
    public function it_dispatches_translation_created_event_on_success()
    {
        Event::fake(TranslationCreated::class);

        $service = $this->app->make(Translation::class);

        $service->addTranslation('New Key', 'en', 'New Value');

        Event::assertDispatched(function (TranslationCreated $event) {
            return $event->locale === 'en' &&
                   $event->key === 'New Key' &&
                   $event->message === 'New Value';
        });
    }

    /** @test */
    public function it_adds_single_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addTranslation('New Key', 'en', 'New Value');

        $message = $service->allTranslations();
        $message = $message['en']['New Key'];

        $this->assertEquals('New Value', $message);
    }

    /** @test */
    public function it_adds_group_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addTranslation('new.messages.nested.message', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['new.messages.nested.message'];

        $this->assertEquals('new value', $message);
    }

    /** @test */
    public function it_adds_vendor_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addTranslation('new::messages.nested.message', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['new::messages.nested.message'];

        $this->assertEquals('new value', $message);
    }

    /** @test */
    public function it_ads_new_database_item()
    {
        $service = $this->app->make(Translation::class);

        $this->assertDatabaseMissing('translations', [
            'key' => 'i18n::animals.dog',
        ]);

        $bool = $service->addTranslation('i18n::animals.dog', 'en', 'A dog barks.');

        $this->assertDatabaseHas('translations', [
            'key' => 'i18n::animals.dog',
        ]);
    }
}
