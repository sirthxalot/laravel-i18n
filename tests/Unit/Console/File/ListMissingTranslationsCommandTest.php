<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\File;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ListMissingTranslationsCommandTest extends FileTestCase
{
    /** @test */
    public function it_list_missing_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('nl');

        $this->artisan('i18n:list-missing-translations')
            ->expectsTable(['LOCALE', 'TRANSLATION KEY'], [
                ['nl', 'Hello :Name'],
                ['nl', 'A dog barks.'],
                ['nl', 'messages.nested.message'],
                ['nl', 'i18n::languages.en'],
                ['nl', 'i18n::languages.foo'],
                ['nl', 'Should not exist yo.'],
                ['nl', 'pluralization.ago'],
                ['nl', 'not.present.anywhere'],
            ]);

        $this->cleanFixtures();
    }
}
