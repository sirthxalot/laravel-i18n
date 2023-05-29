<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Languages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageCreated;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AddLanguageTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->addLanguage('fr_CH');

        $this->assertIsBool($bool);
    }

    /** @test */
    public function it_can_be_true()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->addLanguage('fr_CH');

        $this->assertTrue($bool);
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
    }

    /** @test */
    public function its_available_in_list()
    {
        $service = $this->app->make(Translation::class);

        $this->assertArrayNotHasKey('fr_CH', $service->allLanguages());

        $bool = $service->addLanguage('fr_CH');

        $this->assertArrayHasKey('fr_CH', $service->allLanguages());
    }

    /** @test */
    public function it_ads_new_database_item()
    {
        $service = $this->app->make(Translation::class);

        $this->assertDatabaseMissing('languages', [
            'locale' => 'fr_CH',
        ]);

        $bool = $service->addLanguage('fr_CH');

        $this->assertDatabaseHas('languages', [
            'locale' => 'fr_CH',
        ]);
    }
}
