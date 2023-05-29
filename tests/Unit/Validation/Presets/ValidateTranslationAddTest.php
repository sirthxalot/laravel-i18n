<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Validation\Presets;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ValidateTranslationAddTest extends FileTestCase
{
    /** @test */
    public function its_locale_is_required()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale field is required.');

        $service->addTranslation('new.messages.nested.message', '', '');
    }

    /** @test */
    public function its_locale_must_exist()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale field must be an existing language.');

        $service->addTranslation('new.messages.nested.message', 'foo', '');
    }

    /** @test */
    public function its_key_is_required()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The key field is required.');

        $service->addTranslation('', 'en', '');
    }

    /** @test */
    public function its_key_allows_max_65535_chars()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The key field must not be greater than 65535 characters.');

        $service->addTranslation(str_repeat('a', 65536), 'en', '');
    }

    /** @test */
    public function its_key_must_be_missing()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The key field must be a missing translation key.');

        $service->addTranslation('Hello', 'en', '');
    }

    /** @test */
    public function its_message_allows_max_65535_chars()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The message field must not be greater than 65535 characters.');

        $service->addTranslation('new.messages.nested.message', 'en', str_repeat('a', 65536));
    }
}
