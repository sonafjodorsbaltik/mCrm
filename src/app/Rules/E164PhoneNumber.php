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
                $fail(__('validation.phone_valid'));
            }
        } catch (NumberParseException $e) {
            $fail(__('validation.phone_format'));
        }
    }
}