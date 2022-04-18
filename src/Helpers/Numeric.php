<?php

namespace Vio\Pinball\Helpers;

/**
 * Numeric related helpers.
 *
 * @author Jasper Tey
 *
 */
class Numeric
{
    public static function strip($in, $chars = '.')
    {
        $out = preg_replace("/[^0-9{$chars}]/", "", $in);
        return $out;
    }

    public static function floatval($in)
    {
        $out = static::strip($in);
        return floatval($out);
    }

    public static function money($in)
    {
        $out = static::floatval($in);
        return number_format($out, 2);
    }

    /**
     *
     * via: https://stackoverflow.com/questions/37618679/format-number-to-n-significant-digits-in-php
     *
     * @param $value
     * @param $digits
     * @return float
     */
    public static function sigFig($value, $digits)
    {
        if ($value == 0) {
            $decimalPlaces = $digits - 1;
        } elseif ($value < 0) {
            $decimalPlaces = $digits - floor(log10($value * -1)) - 1;
        } else {
            $decimalPlaces = $digits - floor(log10($value)) - 1;
        }

        $answer = round($value, $decimalPlaces);
        return $answer;
    }

    public static function roundUpTo($value, $precision)
    {
        return round($value, $precision) + 0;
    }

    public static function formatUpTo($value, $decimals)
    {
        $value = number_format($value, $decimals);
        if ($decimals > 0) {
            $value = rtrim($value, 0);
            $value = rtrim($value, '.');
        }
        return $value;
    }

    public static function fraction($value)
    {
        $whole = floor($value);
        $fraction = $value - $whole;

        $parts = [
            'whole' => $whole,
            'fraction' => $fraction,
        ];

        return $parts;
    }
}
