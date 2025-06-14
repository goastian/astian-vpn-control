<?php

namespace App\Http\Requests\Peer;

use App\Models\Server\Wg;
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
            'name' => ['required'],
            'server_id' => [
                'required',
                'exists:wgs,id',
                function ($attribute, $value, $fail) {

                    $wg = Wg::where('id', $value)->first();

                    if (!$wg->active) {
                        $fail("The server is currently offline and cannot be used.");
                    }
                }
            ],
        ];
    }
}
