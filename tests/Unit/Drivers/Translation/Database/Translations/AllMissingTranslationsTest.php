<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllMissingTranslationsTest extends DatabaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Language::factory()->create(['locale' => 'en']);
    }

    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $array = $service->allMissingTranslations('en');

        $this->assertIsArray($array);
    }

    /** @test */
    public function it_finds_missing_single_translations()
    {
        $service = $this->app->make(Translation::class);

        $missingTranslations = $service->allMissingTranslations('en');

        $this->assertArrayHasKey('Hello :Name', $missingTranslations);
    }

    /** @test */
    public function it_finds_missing_group_translations()
    {
        $service = $this->app->make(Translation::class);

        $missingTranslations = $service->allMissingTranslations('en');

        $this->assertArrayHasKey('not.present.anywhere', $missingTranslations);
    }

    /** @test */
    public function it_finds_missing_vendor_translations()
    {
        $service = $this->app->make(Translation::class);

        $missingTranslations = $service->allMissingTranslations('en');

        $this->assertArrayHasKey('i18n::languages.foo', $missingTranslations);
    }

    /** @test */
    public function it_also_works_for_non_existing_languages()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsArray($service->allMissingTranslations(''));
        $this->assertNotEmpty($service->allMissingTranslations(''));

        $this->assertIsArray($service->allMissingTranslations('foo-bar'));
        $this->assertNotEmpty($service->allMissingTranslations('foo-bar'));
    }
}
