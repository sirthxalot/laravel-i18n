<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Console\File;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class ListLanguagesCommandTest extends FileTestCase
{
    /** @test */
    public function it_list_multiple_languages()
    {
        $service = $this->app->make(Translation::class);

        $this->artisan('i18n:list-languages')
            ->expectsTable(['LOCALE', 'NAME'], [
                ['de_CH', 'Swiss German'],
                ['deu_DE', 'German'],
                ['en', 'English'],
                ['zho', 'Chinese'],
                ['es', 'es'],
            ]);
    }
}
