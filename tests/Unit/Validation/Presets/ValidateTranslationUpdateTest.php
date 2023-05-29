<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Validation\Presets;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ValidateTranslationUpdateTest extends FileTestCase
{
    /** @test */
    public function its_locale_is_required()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale field is required.');

        $service->updateTranslation('messages.nested.message', '', '');
    }

    /** @test */
    public function its_locale_must_exist()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale field must be an existing language.');

        $service->updateTranslation('messages.nested.message', 'foo', '');
    }

    /** @test */
    public function its_key_is_required()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The key field is required.');

        $service->updateTranslation('', 'en', '');
    }

    /** @test */
    public function its_key_must_exist()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The key field must be an existing translation key.');

        $service->updateTranslation('messages.nested', 'en', '');
    }

    /** @test */
    public function its_message_allows_max_65535_chars()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The message field must not be greater than 65535 characters.');

        $service->updateTranslation('messages.nested.message', 'en', str_repeat('a', 65536));
    }
}
