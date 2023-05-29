<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Models\Translation as TranslationModel;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllTranslationsTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsArray($service->allTranslations());
    }

    /** @test */
    public function its_may_an_empty_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertEmpty($service->allTranslations());
    }

    /** @test */
    public function it_list_all_languages()
    {
        $service = $this->app->make(Translation::class);

        Language::factory(5)->create();
        TranslationModel::factory(10)->create();

        $allTranslations = $service->allTranslations();
        $allLanguages = $service->allLanguages();

        foreach ($allLanguages as $locale => $name) {
            $this->assertArrayHasKey($locale, $allTranslations);
        }
    }

    /** @test */
    public function it_contains_single_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('Hello', 'en', 'Hello :Name');

        $allTranslations = $service->allTranslations();

        $this->assertEquals('Hello :Name', $allTranslations['en']['Hello']);
    }

    /** @test */
    public function it_contains_group_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('messages.nested.message', 'en', 'nested message');

        $allTranslations = $service->allTranslations();

        $this->assertEquals('nested message', $allTranslations['en']['messages.nested.message']);
    }

    /** @test */
    public function it_contains_vendor_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('i18n::languages.en', 'en', 'English');

        $allTranslations = $service->allTranslations();

        $this->assertEquals('English', $allTranslations['en']['i18n::languages.en']);
    }

    /** @test */
    public function it_contains_empty_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('messages.empty', 'en', '');

        $allTranslations = $service->allTranslations();

        $this->assertEquals('', $allTranslations['en']['messages.empty']);
    }
}
