<?php

namespace Sirthxalot\Laravel\I18n\Tests\Unit\Drivers\Translation\File\Translations;

use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Sirthxalot\Laravel\I18n\Events\Translation\TranslationCreated;
use Sirthxalot\Laravel\I18n\Tests\FileTestCase;
use Sirthxalot\Laravel\I18n\Translation;

class AddTranslationTest extends FileTestCase
{
    /** @test */
    public function its_a_boolean()
    {
        $service = $this->app->make(Translation::class);

        $bool = $service->addTranslation('New Key', 'en', 'New Value');

        $this->assertIsBool($bool);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_throws_a_validation_exception()
    {
        $service = $this->app->make(Translation::class);

        $this->expectException(ValidationException::class);

        $service->addTranslation('Hello', 'en', 'salut');

        $this->cleanFixtures();
    }

    /** @test */
    public function it_dispatches_translation_created_event_on_success()
    {
        Event::fake(TranslationCreated::class);

        $service = $this->app->make(Translation::class);

        $service->addTranslation('New Key', 'en', 'New Value');

        Event::assertDispatched(function (TranslationCreated $event) {
            return $event->locale === 'en' &&
                   $event->key === 'New Key' &&
                   $event->message === 'New Value';
        });

        $this->cleanFixtures();
    }

    /** @test */
    public function it_adds_single_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addTranslation('New Key', 'en', 'New Value');

        $message = $service->allTranslations();
        $message = $message['en']['New Key'];

        $this->assertEquals('New Value', $message);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_adds_group_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addTranslation('new.messages.nested.message', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['new.messages.nested.message'];

        $this->assertEquals('new value', $message);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_adds_vendor_translations()
    {
        $service = $this->app->make(Translation::class);

        $service->addTranslation('new::messages.nested.message', 'en', 'new value');

        $message = $service->allTranslations();
        $message = $message['en']['new::messages.nested.message'];

        $this->assertEquals('new value', $message);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_generate_group_translation_file()
    {
        $service = $this->app->make(Translation::class);

        $path = $this->fixturesPath.'/lang/en/new.php';

        $this->assertFileDoesNotExist($path);

        $service->addTranslation('new.translation.key', 'en', 'New Value');

        $this->assertFileExists($path);

        $this->cleanFixtures();
    }

    /** @test */
    public function it_generate_vendor_translation_file()
    {
        $service = $this->app->make(Translation::class);

        $path = $this->fixturesPath.'/lang/vendor/new/en/new.php';

        $this->assertFileDoesNotExist($path);

        $service->addTranslation('new::new.translation.key', 'en', 'New Value');

        $this->assertFileExists($path);

        $this->cleanFixtures();
    }
}
