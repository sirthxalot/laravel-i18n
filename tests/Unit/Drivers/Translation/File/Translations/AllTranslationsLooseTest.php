<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllTranslationsLooseTest extends FileTestCase
{
    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertIsArray($service->allTranslationsLoose());
    }

    /** @test */
    public function its_may_an_empty_array()
    {
        $this->emptyLangDirectory();

        $service = $this->app->make(Translation::class);

        $this->assertEmpty($service->allTranslationsLoose());

        $this->cleanFixtures();
    }

    /** @test */
    public function it_list_all_languages()
    {
        $service = $this->app->make(Translation::class);

        $allTranslations = $service->allTranslationsLoose();
        $allTranslationKeys = $service->allTranslationKeys();

        foreach ($allTranslationKeys as $tKey => $null) {
            $this->assertArrayHasKey($tKey, $allTranslations['en']);
        }
    }

    /** @test */
    public function it_contains_single_translations()
    {
        $service = $this->app->make(Translation::class);
        $allTranslations = $service->allTranslationsLoose();

        $this->assertEquals('Hello :Name', $allTranslations['en']['Hello']);
    }

    /** @test */
    public function it_contains_group_translations()
    {
        $service = $this->app->make(Translation::class);
        $allTranslations = $service->allTranslationsLoose();

        $this->assertEquals('nested message', $allTranslations['en']['messages.nested.message']);
    }

    /** @test */
    public function it_contains_vendor_translations()
    {
        $service = $this->app->make(Translation::class);
        $allTranslations = $service->allTranslationsLoose();

        $this->assertEquals('English', $allTranslations['en']['i18n::languages.en']);
    }

    /** @test */
    public function it_contains_empty_translations()
    {
        $service = $this->app->make(Translation::class);
        $allTranslations = $service->allTranslationsLoose();

        $this->assertEquals('', $allTranslations['en']['messages.empty']);
    }

    /** @test */
    public function it_contains_null_translations()
    {
        $service = $this->app->make(Translation::class);
        $allTranslations = $service->allTranslationsLoose();

        $this->assertNull($allTranslations['zho']['i18n::languages.de_CH']);
    }
}
