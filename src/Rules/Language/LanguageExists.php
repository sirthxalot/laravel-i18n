<?php

namespace Sirthxalot\Laravel\I18n\Rules\Language;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Rule;
use Sirthxalot\Laravel\I18n\Translation;

class LanguageExists implements Rule
{
    /**
     * Get the validation error message.
     */
    public function message(): array|string
    {
        return __('i18n::validation.language.exists');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @throws BindingResolutionException
     */
    public function passes($attribute, $value): bool
    {
        return app()->make(Translation::class)->languageExists($value);
    }
}
