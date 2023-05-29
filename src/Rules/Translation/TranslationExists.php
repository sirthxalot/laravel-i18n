<?php

namespace Sirthxalot\Laravel\I18n\Rules\Translation;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Validation\Rule;
use Sirthxalot\Laravel\I18n\Translation;

class TranslationExists implements Rule
{
    /**
     * Get the validation error message.
     */
    public function message(): array|string
    {
        return __('i18n::validation.translation.key.exists');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @throws BindingResolutionException
     */
    public function passes($attribute, $value): bool
    {
        $service = app()->make(Translation::class);

        if (request()->has('locale') && request()->get('locale')) {
            return $service->translationExists($value, request()->get('locale'));
        }

        return $service->translationExists($value, request()->get('locale'));
    }
}
