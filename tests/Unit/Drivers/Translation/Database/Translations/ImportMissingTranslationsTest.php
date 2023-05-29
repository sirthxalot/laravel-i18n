<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ImportMissingTranslationsTest extends DatabaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Language::factory()->create(['locale' => 'en']);
    }

    /** @test */
    public function it_can_import_for_given_locale()
    {
        $service = $this->app->make(Translation::class);

        $missingTranslations = $service->allMissingTranslations('en');

        foreach ($missingTranslations as $tKey) {
            $this->assertFalse($service->translationExists($tKey));
        }

        $service->importMissingTranslations('en');

        $missingTranslations = $service->allMissingTranslations('en');

        foreach ($missingTranslations as $tKey) {
            $this->assertTrue($service->translationExists($tKey));
        }
    }
}
