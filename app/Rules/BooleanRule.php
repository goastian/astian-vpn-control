<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BooleanRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!in_array($value, [true, false, 1, 0, '1', '0', 'true', 'false'], true)) {
            $fail(__('The :attribute field must be a valid boolean value.'));
        }

        request()->merge([
            $attribute => in_array($value, [0, '0', 'false']) ? false : true
        ]);
    }
}
