<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneOrEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (!filter_var($value, FILTER_VALIDATE_EMAIL) && !preg_match('/^(\+98|0)?9\d{9}$/', $value)) {
            $fail('The :attribute must be email or phone number.');
        }
    }
}
