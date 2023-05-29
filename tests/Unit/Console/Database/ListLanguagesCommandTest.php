<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ListLanguagesCommandTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_list_multiple_languages()
    {
        $service = $this->app->make(Translation::class);

        $service->addLanguage('en');
        $service->addLanguage('nl');

        $this->artisan('i18n:list-languages')
            ->expectsTable(['LOCALE', 'NAME'], [
                ['en', 'en'],
                ['nl', 'nl'],
            ]);
    }
}
