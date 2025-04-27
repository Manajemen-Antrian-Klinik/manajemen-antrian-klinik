<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    // Rule untuk Nomor Telepon
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }
        
        $cleanValue = preg_replace('/[^0-9]/', '', $value);
        
        // Pengecekan untuk nomor telepon indonesia yang dimulai 08 atau +628 dan sepanjang 10 - 12 digit
        if (!preg_match('/^(08|\+?628)[0-9]{8,12}$/', $cleanValue)) {
            $fail('The :attribute must be a valid phone number.');
        }
    }
}