<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class SetTranslationCommandTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_new_translation()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('en');

        $this->assertFalse($service->translationExists('Excellent', 'en'));

        $this->artisan('i18n:set-translation', [
            'tKey' => 'Excellent',
            'locale' => 'en',
            'message' => 'Excellent',
        ])->expectsOutput('✅  Translation has been created successfully.')
            ->assertSuccessful();

        $this->assertTrue($service->translationExists('Excellent', 'en'));
    }

    /** @test */
    public function it_updates_translation()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('Excellent', 'en', '');

        $this->artisan('i18n:set-translation', [
            'tKey' => 'Excellent',
            'locale' => 'en',
            'message' => 'Excellent',
        ])
            ->expectsConfirmation('Do you still want to continue?', 'yes')
            ->expectsOutput('✅  Translation has been updated successfully.')
            ->assertSuccessful();
    }

    /** @test */
    public function it_works_without_arguments()
    {
        $this->artisan('i18n:set-translation')
            ->expectsQuestion('Which translation key do you want to use?', 'i18n::animals.dog')
            ->expectsOutput('Great choice we assume this is a "vendor" translation key.')
            ->expectsQuestion('Which locale is the translation for?', 'en')
            ->expectsConfirmation('Do you really want to add a new language?', 'yes')
            ->expectsQuestion('Which message do you want to use?', '')
            ->expectsConfirmation('Is this okay?', 'yes')
            ->assertSuccessful();
    }
}
