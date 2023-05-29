<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\File;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AddLanguageCommandTest extends FileTestCase
{
    /** @test */
    public function it_adds_language()
    {
        $service = $this->app->make(Translation::class);

        $this->assertFalse($service->languageExists('nl'));

        $this->artisan('i18n:add-language', ['language' => 'nl'])->assertSuccessful();

        $this->assertTrue($service->languageExists('nl'));

        $this->cleanFixtures();
    }

    /** @test */
    public function it_ask_for_locale()
    {
        $this->artisan('i18n:add-language')
            ->expectsQuestion('Which language (locale) do you want to create?', 'nl')
            ->expectsOutput('✅  New language has been created successfully.');

        $this->cleanFixtures();
    }

    /** @test */
    public function it_tells_you_if_a_locale_exist()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('nl');

        $this->artisan('i18n:add-language', ['language' => 'nl'])
            ->expectsOutput('⛔️ The language could not been created.')
            ->expectsOutput('➡️ Language already exists.');

        $this->cleanFixtures();
    }

    /** @test */
    public function it_tells_you_if_a_locale_is_invalid()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('nl');

        $this->artisan('i18n:add-language', ['language' => 'fooooo'])
            ->expectsOutput('⛔️ The language could not been created.')
            ->expectsOutput('➡️  Invalid data follow convention e.g. "eng_US".');

        $this->cleanFixtures();
    }
}
