<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Languages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllLanguagesTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $allLanguages = $service->allLanguages();

        $this->assertIsArray($allLanguages);
    }

    /** @test */
    public function it_can_be_empty()
    {
        $service = $this->app->make(Translation::class);

        $this->assertEmpty($service->allLanguages());
    }

    /** @test */
    public function it_finds_language_models()
    {
        Language::factory()->create(['locale' => 'en']);

        $service = $this->app->make(Translation::class);

        $this->assertContains('en', $service->allLanguages());
    }
}
