<?php

namespace Sirthxalot\Laravel\I18n\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Sirthxalot\Laravel\I18n\Rules\Iso\Iso15897;

class SetTranslationRequest extends FormRequest
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
            'locale' => ['required', 'min:2', 'max:6', new Iso15897()],
            'key' => ['required', 'max:65535'],
            'message' => ['max:65535'],
        ];
    }
}
