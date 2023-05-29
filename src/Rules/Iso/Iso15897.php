<?php

namespace Sirthxalot\Laravel\I18n\Rules\Iso;

use Illuminate\Contracts\Validation\Rule;

class Iso15897 implements Rule
{
    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('i18n::validation.iso.15897');
    }

    /**
     * Determine if the validation passes.
     */
    public function passes($attribute, $value): bool
    {
        /**
         * The value must follow the ISO-15897 convention e.g.
         * "en", "deu", "en_US", "deu_DE". It starts with two-
         * or three-letter in lowercase optional followed by an
         * underscore and two letters in uppercase.
         */
        return (bool) preg_match('/^[a-z]{2,3}(?:_[A-Z]{2})?$/', $value);
    }
}
