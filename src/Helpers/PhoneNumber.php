<?php

namespace Vio\Pinball\Helpers;

class PhoneNumber
{
    public static function extract($raw, $options = [])
    {
        // Strip non numerics
        $numberCleaned = preg_replace('/[^0-9,.]/', '', $raw);

        $digits = str_split($numberCleaned);

        $number = '';
        $c = 0;
        while (++$c <= 7 && $digits) {
            $digit = array_pop($digits);
            $number .= $digit;
        }
        $number = strrev($number);

        $areaCode = '';
        $c = 0;
        while (++$c <= 3 && $digits) {
            $digit = array_pop($digits);
            $areaCode .= $digit;
        }
        $areaCode = strrev($areaCode);

        $countryCode = '';
        while ($digits) {
            $digit = array_pop($digits);
            $countryCode .= $digit;
        }
        $countryCode = strrev($countryCode);

        $digits = $numberCleaned;

        return compact('countryCode', 'areaCode', 'number', 'digits', 'raw');
    }

    public static function format($number, $o = [])
    {
        $defaults = [
            'exclude' => [],
        ];
        $o += $defaults;

        $parts = static::extract($number);
        $tokens = [];

        if (! in_array('countryCode', $o['exclude']) && $part = $parts['countryCode']) {
            $tokens[] = $part;
        }

        if (! in_array('areaCode', $o['exclude']) && $part = $parts['areaCode']) {
            $tokens[] = $part;
        }

        if (! in_array('number', $o['exclude']) && $part = $parts['number']) {
            // Add Dash
            $part = substr_replace($part, '-', 3, 0);

            $tokens[] = $part;
        }

        $formatted = implode('-', $tokens);

        return $formatted;
    }
}
