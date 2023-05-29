<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Illuminate\Support\Facades\Event;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationDeleted;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class RemoveTranslationTest extends FileTestCase
{
    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $isDeleted = $service->removeTranslation('', '');
        $this->assertIsBool($isDeleted);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_can_be_false()
    {
        $service = $this->app->make(Translation::class);

        $isDeleted = $service->removeTranslation('', '');
        $this->assertFalse($isDeleted);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_can_be_true()
    {
        $service = $this->app->make(Translation::class);

        $isDeleted = $service->removeTranslation('messages.nested.message', 'en');
        $this->assertTrue($isDeleted);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_dispatches_translation_deleted_on_success()
    {
        Event::fake(TranslationDeleted::class);

        $service = $this->app->make(Translation::class);

        $isDeleted = $service->removeTranslation('messages.nested.message', 'en');

        Event::assertDispatched(function (TranslationDeleted $event) {
            return $event->key === 'messages.nested.message' &&
                   $event->locale === 'en';
        });

        $this->cleanFixtures();
    }

    /** @test */
    public function it_deletes_a_single_translation()
    {
        $service = $this->app->make(Translation::class);

        $hasTranslation = $service->translationExists('Hello', 'en');
        $this->assertTrue($hasTranslation);

        $isDeleted = $service->removeTranslation('Hello', 'en');

        $hasTranslation = $service->translationExists('Hello', 'en');
        $this->assertFalse($hasTranslation);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_deletes_a_group_translation()
    {
        $service = $this->app->make(Translation::class);

        $hasTranslation = $service->translationExists('messages.nested.message', 'en');
        $this->assertTrue($hasTranslation);

        $isDeleted = $service->removeTranslation('messages.nested.message', 'en');

        $hasTranslation = $service->translationExists('messages.nested.message', 'en');
        $this->assertFalse($hasTranslation);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_deletes_a_vendor_translation()
    {
        $service = $this->app->make(Translation::class);

        $hasTranslation = $service->translationExists('i18n::messages.nested.message', 'en');
        $this->assertTrue($hasTranslation);

        $isDeleted = $service->removeTranslation('i18n::messages.nested.message', 'en');

        $hasTranslation = $service->translationExists('i18n::messages.nested.message', 'en');
        $this->assertFalse($hasTranslation);

        $this->cleanFixtures();
    }
}
