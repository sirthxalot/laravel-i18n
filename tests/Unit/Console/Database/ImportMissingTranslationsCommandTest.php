<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ImportMissingTranslationsCommandTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_imports_missing_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('en');

        $this->assertFalse($service->translationExists('A dog barks.', 'en'));

        $this->artisan('i18n:import-missing-translations', ['language' => 'en'])->assertSuccessful();

        $this->assertTrue($service->translationExists('A dog barks.', 'en'));
    }
}
