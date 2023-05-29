<?php

namespace Sirthxalot\Laravel\I18n\Events\Language;

use Illuminate\Foundation\Events\Dispatchable;

class LanguageDeleted
{
    use Dispatchable;

    /**
     * Create a new language deleted event instance.
     *
     * @since  1.0.0
     *
     * @param  string  $locale  "en_US"
     */
    public function __construct(public readonly string $locale)
    {
        // ...
    }
}
