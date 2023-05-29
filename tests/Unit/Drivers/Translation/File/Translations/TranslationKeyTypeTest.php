<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class TranslationKeyTypeTest extends FileTestCase
{
    /** @test */
    public function its_a_string()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsString($service->translationKeyType(''));
    }

    /** @test */
    public function it_detects_single_types()
    {
        $service = $this->app->make(Translation::class);

        $this->assertEquals('single', $service->translationKeyType('hello'));
    }

    /** @test */
    public function it_detects_group_types()
    {
        $service = $this->app->make(Translation::class);

        $this->assertEquals('group', $service->translationKeyType('messages.nested.message'));
    }

    /** @test */
    public function it_detects_vendor_types()
    {
        $service = $this->app->make(Translation::class);

        $this->assertEquals('vendor', $service->translationKeyType('i18n::messages.nested.message'));
    }
}
