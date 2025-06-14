<?php

namespace App\Http\Requests\Wireguard;

use App\Models\Server\Wg;
use App\Rules\BooleanRule;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => [
                'required',
                'max:50',
                function ($attribute, $value, $fail) {
                    $wg = Wg::where('slug', Str::slug($value, "-"))->first();

                    if ($wg && request()->server_id && $wg->server_id == request()->server_id) {
                        $fail("The $value has already been registered on this server.");
                    }
                }
            ],
            'listen_port' => ['required', 'max:10', 'unique:wgs,listen_port'],
            'interface' => ['required', 'max:50'],
            'server_id' => ['required', 'exists:servers,id'],
            'enable_dns' => ['nullable', new BooleanRule()],
            'dns' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!empty($value)) {
                        if (!filter_var($value, FILTER_VALIDATE_IP)) {
                            return $fail('The DNS must be a valid IP address.');
                        }
                    }

                },
                'max:190'
            ],
        ];
    }
}
