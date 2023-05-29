<?php

namespace Sirthxalot\Laravel\I18n\Events\Translation;

use Illuminate\Foundation\Events\Dispatchable;

class TranslationCreated
{
    use Dispatchable;

    /**
     * Create a new translation created event instance.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     * @param  string  $message  "A dog barks"
     */
    public function __construct(
        public readonly string $key,
        public readonly string $locale,
        public readonly string $message,
    ) {
        // ...
    }
}
