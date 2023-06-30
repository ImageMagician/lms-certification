<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class PhoneValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phone = $this->phone_process($value);
        $exchange = $this->phone_exchange($phone);
        $ac = $this->area_code($phone);

        if ( !$exchange || !$ac ) {
            $fail('Invalid phone number');
        }
    }

    private function phone_process( $tel ) {
        /*
         * This cleans up the phone number of all non-numeric characters and leading USA country code.
         */

        // Sanitize for peace of mind (piece of mind?)
        $phone = htmlentities($tel);

        // remove everything except numbers
        $phone = preg_replace("/[^0-9]/", '', $phone);

        // check if they added the "1" to the start of the number. If so, remove it.
        if ( strlen( $phone ) == 11 && $phone[0] == 1 ) {
            $phone = substr( $phone, 1);
        }
        return $phone;
    }

    private function phone_exchange( $tel ) {
        /*
         * Checks the phone exchange (prefix) for valid numbers.
         * Pulls from the database of invalid exchange numbers 'usa
         */

        // Grab only the exchange code portion of the phone number (4th-6th characters)
        $ph_ex = substr($tel, 3, 3);
        $ac_check = DB::table('usa_phone_exchange_bad')->where('area_code', $ph_ex)->first();

        // exchange numbers can't start with a 0 or 1
        if ( $ph_ex < 201 || $ac_check !== null) {
            return false;
        }
        else {
            return true;
        }
    }

    private function area_code( $tel ) {
        // check for valid area code from usa_area_codes
        // Get area code
        $ac = substr( $tel, 0, 3 );
        $le = DB::table('usa_area_codes')->where('area_code', $ac);

        if ( $le == null ) {
            return false;
        }
        else {
            return true;
        }
    }

}
