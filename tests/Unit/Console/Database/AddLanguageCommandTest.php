<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AddLanguageCommandTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_adds_language()
    {
        $service = $this->app->make(Translation::class);

        $this->assertFalse($service->languageExists('en'));

        $this->artisan('i18n:add-language', ['language' => 'en'])->assertSuccessful();

        $this->assertTrue($service->languageExists('en'));
    }

    /** @test */
    public function it_ask_for_locale()
    {
        $this->artisan('i18n:add-language')
            ->expectsQuestion('Which language (locale) do you want to create?', 'en')
            ->expectsOutput('✅  New language has been created successfully.');
    }

    /** @test */
    public function it_tells_you_if_a_locale_exist()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('en');

        $this->artisan('i18n:add-language', ['language' => 'en'])
            ->expectsOutput('⛔️ The language could not been created.')
            ->expectsOutput('➡️ Language already exists.');
    }

    /** @test */
    public function it_tells_you_if_a_locale_is_invalid()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('en');

        $this->artisan('i18n:add-language', ['language' => 'fooooo'])
            ->expectsOutput('⛔️ The language could not been created.')
            ->expectsOutput('➡️  Invalid data follow convention e.g. "eng_US".');
    }
}
