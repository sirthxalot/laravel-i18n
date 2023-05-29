<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Models\Translation as TranslationModel;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllTranslationsHorizontalTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsArray($service->allTranslationsHorizontal());
    }

    /** @test */
    public function its_may_an_empty_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertEmpty($service->allTranslationsHorizontal());
    }

    /** @test */
    public function it_contains_single_keys()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('Hello', 'en', '');

        $this->assertArrayHasKey('Hello', $service->allTranslationsHorizontal());
    }

    /** @test */
    public function it_contains_group_keys()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('messages.empty', 'en', '');

        $this->assertArrayHasKey('messages.empty', $service->allTranslationsHorizontal());
    }

    /** @test */
    public function it_contains_vendor_keys()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('i18n::languages.en', 'en', '');

        $this->assertArrayHasKey('i18n::languages.en', $service->allTranslationsHorizontal());
    }

    /** @test */
    public function it_contains_all_keys()
    {
        $service = $this->app->make(Translation::class);

        TranslationModel::factory(6)->create();

        $allKeys = $service->allTranslationKeys();

        foreach ($allKeys as $key => $null) {
            $this->assertArrayHasKey($key, $service->allTranslationsHorizontal());
        }
    }

    /** @test */
    public function its_item_contains_all_languages()
    {
        $service = $this->app->make(Translation::class);

        Language::factory()->create(['locale' => 'en']);
        Language::factory(5)->create();

        $allLanguages = $service->allLanguages();

        $service->setTranslation('Hello', 'en', '');

        foreach ($allLanguages as $locale => $name) {
            $this->assertArrayHasKey($locale, $service->allTranslationsHorizontal()['Hello']);
        }
    }
}
