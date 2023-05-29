<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class TranslationExistsTest extends FileTestCase
{
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

        $this->assertTrue($service->translationExists('messages.empty', 'en'));
    }

    /** @test */
    public function it_can_be_false()
    {
        $service = $this->app->make(Translation::class);

        $this->assertFalse($service->translationExists('messages.empty', 'foo'));
        $this->assertFalse($service->translationExists('footest', 'en'));
    }
}
