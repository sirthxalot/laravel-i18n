<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Languages;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class LanguageExistsTest extends DatabaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Language::factory()->create(['locale' => 'en']);
    }

    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->languageExists('');

        $this->assertIsBool($bool);
        $this->assertFalse($bool);
    }

    /** @test */
    public function it_can_be_true()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->languageExists('en');

        $this->assertTrue($bool);
    }

    /** @test */
    public function it_can_be_false()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->languageExists('ar');

        $this->assertFalse($bool);
    }
}
