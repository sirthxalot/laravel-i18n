<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Languages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Sirthxalot\Laravel\I18n\Events\Language\LanguageDeleted;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Models\Translation as TranslationModel;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class RemoveLanguageTest extends DatabaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Language::factory()
            ->has(TranslationModel::factory(5))
            ->create(['locale' => 'en']);
    }

    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->removeLanguage('');

        $this->assertIsBool($bool);
        $this->assertFalse($bool);
    }

    /** @test */
    public function it_can_be_false()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->removeLanguage('');

        $this->assertFalse($bool);
    }

    /** @test */
    public function it_can_be_true()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->removeLanguage('en');

        $this->assertTrue($bool);
    }

    /** @test */
    public function it_dispatches_language_deleted_event_on_success()
    {
        Event::fake(LanguageDeleted::class);

        $service = $this->app->make(Translation::class);

        $service->removeLanguage('en');

        Event::assertDispatched(function (LanguageDeleted $event) {
            return $event->locale === 'en';
        });
    }

    /** @test */
    public function it_removes_it_from_all_languages()
    {
        $service = $this->app->make(Translation::class);

        $allLanguages = $service->allLanguages();
        $this->assertArrayHasKey('en', $allLanguages);

        $service->removeLanguage('en');

        $allLanguages = $service->allLanguages();
        $this->assertArrayNotHasKey('en', $allLanguages);
    }

    /** @test */
    public function it_removes_all_related_translations()
    {
        $service = $this->app->make(Translation::class);

        $allTranslations = $service->allTranslations();

        $this->assertArrayHasKey('en', $allTranslations);
        $this->assertCount(5, $allTranslations['en']);

        $service->removeLanguage('en');

        $allTranslations = $service->allTranslations();

        $this->assertEmpty($allTranslations);
    }
}
