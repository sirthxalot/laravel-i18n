<?php

namespace Sirthxalot\Laravel\I18n\Tests\Http\Middleware;

use Sirthxalot\Laravel\I18n\Http\Middleware\SetLocaleFromGetParameter;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;

class SetLocaleFromGetParameterTest extends FileTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        resolve('router')
            ->get('/', function () {
                return '';
            })
            ->middleware(SetLocaleFromGetParameter::class);
    }

    /** @test */
    public function it_changes_the_app_locale()
    {
        $this->assertEquals('en', app()->getLocale());

        $this->withoutExceptionHandling()
            ->withMiddleware(SetLocaleFromGetParameter::class)
            ->get('/?locale=zho');

        $this->assertEquals('zho', app()->getLocale());
    }
}
