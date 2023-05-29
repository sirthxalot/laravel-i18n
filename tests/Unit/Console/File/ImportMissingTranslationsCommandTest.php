<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\File;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ImportMissingTranslationsCommandTest extends FileTestCase
{
    /** @test */
    public function it_imports_missing_translations()
    {
        $service = $this->app->make(Translation::class);

        $this->assertFalse($service->translationExists('A dog barks.', 'en'));

        $this->artisan('i18n:import-missing-translations', ['language' => 'en'])->assertSuccessful();

        $this->assertTrue($service->translationExists('A dog barks.', 'en'));
    }
}
