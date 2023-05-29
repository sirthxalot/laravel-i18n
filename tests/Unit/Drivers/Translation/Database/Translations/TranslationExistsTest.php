<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class TranslationExistsTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsBool($service->translationExists('', ''));
    }

    /** @test */
    public function it_can_be_true()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('messages.empty', 'en', '');

        $this->assertTrue($service->translationExists('messages.empty', 'en'));
    }

    /** @test */
    public function it_can_be_false()
    {
        $service = $this->app->make(Translation::class);

        $this->assertFalse($service->translationExists('messages.empty', 'en'));
    }
}
