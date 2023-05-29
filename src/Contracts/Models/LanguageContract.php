<?php

namespace Sirthxalot\Laravel\I18n\Contracts\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $locale "en_US"
 */
interface LanguageContract
{
    /**
     * Get the language's name e.g. "English".
     *
     * @since  1.0.0
     */
    public function getNameAttribute(): string;

    /**
     * Get all translations for the language.
     *
     * @since  1.0.0
     */
    public function translations(): HasMany;
}
