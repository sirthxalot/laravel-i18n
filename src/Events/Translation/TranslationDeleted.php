<?php

namespace Sirthxalot\Laravel\I18n\Events\Translation;

use Illuminate\Foundation\Events\Dispatchable;

class TranslationDeleted
{
    use Dispatchable;

    /**
     * Create a new translation deleted event instance.
     *
     * @since  1.0.0
     *
     * @param  string  $key  "i18n::animals.dog"
     * @param  string  $locale  "en_US"
     */
    public function __construct(
        public readonly string $key,
        public readonly string $locale,
    ) {
        // ...
    }
}
