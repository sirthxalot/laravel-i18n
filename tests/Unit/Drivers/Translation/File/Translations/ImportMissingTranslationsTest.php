<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ImportMissingTranslationsTest extends FileTestCase
{
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

        $this->cleanFixtures();
    }
}
