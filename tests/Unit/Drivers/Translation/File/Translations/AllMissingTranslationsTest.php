<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllMissingTranslationsTest extends FileTestCase
{
    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsArray($service->allMissingTranslations('en'));
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
        $this->assertIsArray($service->allMissingTranslations('foo-bar'));
    }
}
