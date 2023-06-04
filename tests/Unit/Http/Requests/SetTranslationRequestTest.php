<?php

namespace Sirthxalot\Laravel\I18n\Tests\Http\Requests;

use Sirthxalot\Laravel\I18n\Http\Requests\SetTranslationRequest;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;

class SetTranslationRequestTest extends FileTestCase
{
    /** @test */
    public function its_locale_is_required()
    {
        $fakeRequest = request()->merge([
            'key' => 'test',
            'locale' => '',
            'message' => '',
        ]);

        $this->expectExceptionMessage('The locale field is required.');

        $request = app(SetTranslationRequest::class);
    }

    /** @test */
    public function its_locale_is_min_2_chars()
    {
        $fakeRequest = request()->merge([
            'key' => 'test',
            'locale' => 'a',
            'message' => '',
        ]);

        $this->expectExceptionMessage('The locale field must be at least 2 characters.');

        $request = app(SetTranslationRequest::class);
    }

    /** @test */
    public function its_locale_is_max_6_chars()
    {
        $fakeRequest = request()->merge([
            'key' => 'test',
            'locale' => str_repeat('a', 7),
            'message' => '',
        ]);

        $this->expectExceptionMessage('The locale field must not be greater than 6 characters.');

        $request = app(SetTranslationRequest::class);
    }

    /** @test */
    public function its_locale_must_follow_iso_15897()
    {
        $fakeRequest = request()->merge([
            'key' => 'test',
            'locale' => str_repeat('a', 4),
            'message' => '',
        ]);

        $this->expectExceptionMessage('The locale does not match the ISO-15897 convention e.g. "en_US".');

        $request = app(SetTranslationRequest::class);
    }

    /** @test */
    public function its_key_is_required()
    {
        $fakeRequest = request()->merge([
            'key' => '',
            'locale' => 'en_US',
            'message' => '',
        ]);

        $this->expectExceptionMessage('The key field is required.');

        $request = app(SetTranslationRequest::class);
    }

    /** @test */
    public function its_key_allows_max_65535_chars()
    {
        $fakeRequest = request()->merge([
            'key' => str_repeat('a', 65536),
            'locale' => 'en_US',
            'message' => '',
        ]);

        $this->expectExceptionMessage('The key field must not be greater than 65535 characters.');

        $request = app(SetTranslationRequest::class);
    }

    /** @test */
    public function its_message_allows_max_65535_chars()
    {
        $fakeRequest = request()->merge([
            'key' => 'auth.failed',
            'locale' => 'en_US',
            'message' => str_repeat('a', 65536),
        ]);

        $this->expectExceptionMessage('The message field must not be greater than 65535 characters.');

        $request = app(SetTranslationRequest::class);
    }
}
