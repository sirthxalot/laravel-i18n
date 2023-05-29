<?php

namespace Sirthxalot\Laravel\I18n\Events\Translation;

use Illuminate\Foundation\Events\Dispatchable;

class TranslationUpdated
{
    use Dispatchable;

    /**
     * Create a new translation updated event instance.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     * @param  string  $message_before  "original message"
     * @param  string  $message_after  "updated message"
     */
    public function __construct(
        public readonly string $key,
        public readonly string $locale,
        public readonly string $message_before,
        public readonly string $message_after,
    ) {
        // ...
    }
}
