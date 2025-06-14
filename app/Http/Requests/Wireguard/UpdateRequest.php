<?php

namespace App\Http\Requests\Wireguard;

use App\Rules\BooleanRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'enable_dns' => ['required', new BooleanRule()],
            'dns' => [
                'nullable',
                'max:190',
                function ($attribute, $value, $fail) {
                    if (request()->has('enable_dns') && empty($value)) {
                        $fail("The field is required");
                    }
                }
            ],
        ];
    }
}
