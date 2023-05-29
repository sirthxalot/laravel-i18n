<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ListMissingTranslationsCommandTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_list_missing_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('en');

        $this->artisan('i18n:list-missing-translations')
            ->expectsTable(['LOCALE', 'TRANSLATION KEY'], [
                ['en', 'Hello :Name'],
                ['en', 'A dog barks.'],
                ['en', 'messages.nested.message'],
                ['en', 'i18n::languages.en'],
                ['en', 'i18n::languages.foo'],
                ['en', 'Should not exist yo.'],
                ['en', 'pluralization.ago'],
                ['en', 'not.present.anywhere'],
            ]);
    }
}
