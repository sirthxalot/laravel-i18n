<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllTranslationKeysTest extends FileTestCase
{
    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsArray($service->allTranslationKeys());
    }

    /** @test */
    public function its_may_an_empty_array()
    {
        $this->emptyLangDirectory();

        $service = $this->app->make(Translation::class);

        $this->assertEmpty($service->allTranslationKeys());

        $this->cleanFixtures();
    }

    /** @test */
    public function it_list_single_keys()
    {
        $service = $this->app->make(Translation::class);

        $this->assertArrayHasKey('Hello', $service->allTranslationKeys());
    }

    /** @test */
    public function it_list_group_keys()
    {
        $service = $this->app->make(Translation::class);

        $this->assertArrayHasKey('messages.empty', $service->allTranslationKeys());
    }

    /** @test */
    public function it_list_vendor_keys()
    {
        $service = $this->app->make(Translation::class);

        $this->assertArrayHasKey('i18n::languages.en', $service->allTranslationKeys());
    }
}
