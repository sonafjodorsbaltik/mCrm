<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

class E164PhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $numberProto = $phoneUtil->parse($value, null);

            if (!$phoneUtil->isValidNumber($numberProto)) {
                $fail('The :attribute must be a valid phone number.');
            }
        } catch (NumberParseException $e) {
            $fail('The :attribute format is invalid.');
        }
    }
}