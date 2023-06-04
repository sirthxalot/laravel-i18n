<?php

namespace Sirthxalot\Laravel\I18n\Tests\Http\Requests;

use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AddLanguageRequestTest extends FileTestCase
{
    /** @test */
    public function it_throws_a_validation_exception()
    {
        $service = $this->app->make(Translation::class);

        $this->expectException(ValidationException::class);

        $service->addLanguage('');
    }

    /** @test */
    public function its_locale_is_required()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale field is required.');

        $service->addLanguage('');
    }

    /** @test */
    public function its_locale_is_min_2_chars()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale field must be at least 2 characters.');

        $service->addLanguage('e');
    }

    /** @test */
    public function its_locale_is_max_6_chars()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale field must not be greater than 6 characters.');

        $service->addLanguage(str_repeat('a', 7));
    }

    /** @test */
    public function its_locale_must_follow_iso_15897()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale does not match the ISO-15897 convention e.g. "en_US".');

        $service->addLanguage('aaaa');
    }

    /** @test */
    public function its_locale_must_be_missing()
    {
        $service = $this->app->make(Translation::class);

        $this->expectExceptionMessage('The locale field must be a missing language.');

        $service->addLanguage('en');
    }
}
