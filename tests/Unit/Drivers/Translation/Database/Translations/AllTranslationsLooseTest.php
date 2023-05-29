<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\Database\Translations;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Sirthxalot\Laravel\I18n\Models\Language;
use Sirthxalot\Laravel\I18n\Models\Translation as TranslationModel;
use Sirthxalot\Laravel\I18n\Tests\DatabaseTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AllTranslationsLooseTest extends DatabaseTestCase
{
    use RefreshDatabase;

    /** @test */
    public function its_an_array()
    {
        $service = $this->app->make(Translation::class);

        $array = $service->allTranslationsLoose();

        $this->assertIsArray($array);
    }

    /** @test */
    public function its_may_an_empty_array()
    {
        $service = $this->app->make(Translation::class);

        $this->assertEmpty($service->allTranslationsLoose());
    }

    /** @test */
    public function it_list_all_languages()
    {
        $service = $this->app->make(Translation::class);

        TranslationModel::factory(8)->create(['locale' => 'en']);

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

        $service->setTranslation('Hello', 'en', 'Hello :Name');

        $allTranslations = $service->allTranslationsLoose();

        $this->assertEquals('Hello :Name', $allTranslations['en']['Hello']);
    }

    /** @test */
    public function it_contains_group_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('messages.nested.message', 'en', 'nested message');

        $allTranslations = $service->allTranslationsLoose();

        $this->assertEquals('nested message', $allTranslations['en']['messages.nested.message']);
    }

    /** @test */
    public function it_contains_vendor_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('i18n::languages.en', 'en', 'English');

        $allTranslations = $service->allTranslationsLoose();

        $this->assertEquals('English', $allTranslations['en']['i18n::languages.en']);
    }

    /** @test */
    public function it_contains_empty_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->setTranslation('messages.empty', 'en', '');

        $allTranslations = $service->allTranslationsLoose();

        $this->assertEquals('', $allTranslations['en']['messages.empty']);
    }

    /** @test */
    public function it_contains_null_translations()
    {
        $service = $this->app->make(Translation::class);

        Language::factory()->create(['locale' => 'zho']);
        Language::factory()->create(['locale' => 'en']);

        $service->setTranslation('i18n::languages.de_CH', 'en', '');

        $allTranslations = $service->allTranslationsLoose();

        $this->assertNull($allTranslations['zho']['i18n::languages.de_CH']);
    }
}
