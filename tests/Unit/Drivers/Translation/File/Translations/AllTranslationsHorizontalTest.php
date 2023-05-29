<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllTranslationsHorizontalTest extends FileTestCase
{
    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsArray($service->allTranslationsHorizontal());
    }

    /** @test */
    public function its_may_an_empty_array()
    {
        $this->emptyLangDirectory();

        $service = $this->app->make(Translation::class);

        $this->assertEmpty($service->allTranslationsHorizontal());

        $this->cleanFixtures();
    }

    /** @test */
    public function it_contains_single_keys()
    {
        $service = $this->app->make(Translation::class);

        $this->assertArrayHasKey('Hello', $service->allTranslationsHorizontal());
    }

    /** @test */
    public function it_contains_group_keys()
    {
        $service = $this->app->make(Translation::class);

        $this->assertArrayHasKey('messages.empty', $service->allTranslationsHorizontal());
    }

    /** @test */
    public function it_contains_vendor_keys()
    {
        $service = $this->app->make(Translation::class);

        $this->assertArrayHasKey('i18n::languages.en', $service->allTranslationsHorizontal());
    }

    /** @test */
    public function it_contains_all_keys()
    {
        $service = $this->app->make(Translation::class);

        $allKeys = $service->allTranslationKeys();

        foreach ($allKeys as $key => $null) {
            $this->assertArrayHasKey($key, $service->allTranslationsHorizontal());
        }
    }

    /** @test */
    public function its_item_contains_all_languages()
    {
        $service = $this->app->make(Translation::class);

        $allLanguages = $service->allLanguages();

        foreach ($allLanguages as $locale => $name) {
            $this->assertArrayHasKey($locale, $service->allTranslationsHorizontal()['Hello']);
        }
    }
}
