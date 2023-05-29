<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllTranslationKeysTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsArray($service->allTranslationKeys());
    }

    /** @test */
    public function its_may_an_empty_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertEmpty($service->allTranslationKeys());
    }

    /** @test */
    public function it_list_single_keys()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('Hello', 'en', '');

        $this->assertArrayHasKey('Hello', $service->allTranslationKeys());
    }

    /** @test */
    public function it_list_group_keys()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('messages.empty', 'en', '');

        $this->assertArrayHasKey('messages.empty', $service->allTranslationKeys());
    }

    /** @test */
    public function it_list_vendor_keys()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('i18n::languages.en', 'en', '');

        $this->assertArrayHasKey('i18n::languages.en', $service->allTranslationKeys());
    }
}
