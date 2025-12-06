<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\NumberParseException;

/**
 * Custom validation rule for E.164 phone number format.
 * 
 * Uses Google's libphonenumber library to validate
 * that phone numbers are in valid international format.
 * 
 * @example +14155552671 (valid US number)
 * @example +380501234567 (valid Ukraine number)
 */
class E164PhoneNumber implements ValidationRule
{
    /**
     * Validate the phone number.
     *
     * @param string $attribute The attribute being validated
     * @param mixed $value The phone number value
     * @param Closure $fail Callback to call on validation failure
     */
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