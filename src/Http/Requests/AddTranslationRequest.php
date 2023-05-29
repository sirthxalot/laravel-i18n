<?php

namespace Sirthxalot\Laravel\I18n\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Sirthxalot\Laravel\I18n\Rules\Language\LanguageExists;
use Sirthxalot\Laravel\I18n\Rules\Translation\TranslationMissing;

class AddTranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'locale' => ['required', new LanguageExists()],
            'key' => ['required', 'max:65535', new TranslationMissing()],
            'message' => ['max:65535'],
        ];
    }
}
