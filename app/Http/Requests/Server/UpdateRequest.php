<?php

namespace App\Http\Requests\Server;

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
            'country' => ['nullable', 'max:190'],
            'ip' => ['required', 'ipv4'],
            'port' => ['nullable', 'max:6'],
            'url' => ['nullable', 'max:100'],
            'client_port' => ['nullable', 'max:5'],
            'socks5_port' => ['nullable', 'max:5']
        ];
    }
}
