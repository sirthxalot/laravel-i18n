<?php

namespace Sirthxalot\Laravel\I18n\Contracts\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string  $locale  "en_US"
 * @property string  $key  "i18n::animals.dog"
 * @property string  $message  "A dog barks."
 */
interface TranslationContract
{
    /**
     * Get the language that belongs to the translation.
     *
     * @since  1.0.0
     */
    public function language(): BelongsTo;
}
