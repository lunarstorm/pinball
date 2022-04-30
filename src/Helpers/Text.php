<?php

namespace Vio\Pinball\Helpers;

/**
 * Text Helpers.
 */
class Text
{
    /**
     * Contains a cache map of previously humanized words.
     *
     * @var array
     */
    protected static $_humanized = [];

    public static function acronym($string)
    {
        $string = trim($string ?? '');

        if (!strlen($string)) {
            return $string;
        }

        $words = explode(" ", $string);
        $acronym = "";

        foreach ($words as $w) {
            $acronym .= $w[0];
        }

        return $acronym;
    }

    /**
     *
     * @param  string  $needle
     * @param  string  $haystack
     */
    public static function startsWith($needle, $haystack)
    {
        return !strncmp($haystack, $needle, strlen($needle));
    }

    /**
     *
     * @param  string  $needle
     * @param  string  $haystack
     */
    public static function endsWith($needle, $haystack)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function properize($string)
    {
        return $string . '\'' . ($string[strlen($string) - 1] != 's' ? 's' : '');
    }

    public static function generateString($length = 8)
    {
        $string = '';
        $vowels = ["a", "e", "i", "o", "u"];
        $consonants = [
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z',
        ];
        $max = $length / 2;
        for ($i = 1; $i <= $max; $i++) {
            $string .= $consonants[rand(0, 19)];
            $string .= $vowels[rand(0, 4)];
        }
        return $string;
    }

    /**
     * Returns the first $wordsreturned out of $string.  If string
     * contains fewer words than $wordsreturned, the entire string
     * is returned.
     *
     * via http://www.nutt.net/2004/12/29/php-a-function-to-return-the-first-n-words-from-a-string/
     *
     * @param  string  $string  The string to shorten.
     * @param  int  $wordsreturned  Number of words to shorten to.
     * @return string The shortened result.
     */
    public static function shorten($string, $wordsreturned)
    {
        $retval = $string;      //  Just in case of a problem

        $array = explode(" ", $string);
        if (count($array) <= $wordsreturned) {
            /*  Already short enough, return the whole thing
             */
            $retval = $string;
        } else {
            /*  Need to chop of some words
             */
            array_splice($array, $wordsreturned);
            $retval = implode(" ", $array) . " ...";
        }
        return $retval;
    }

    public static function truncate($string, $n, $suffix = '')
    {
        $string = trim($string ?? '');

        if (strlen($string) <= $n) {
            return $string;
        }

        $chopped = substr($string, 0, $n);

        return "{$chopped}{$suffix}";
    }

    public static function numeric($str)
    {
        return preg_replace("/[^0-9.]/", "", $str);
    }

    public static function emptyToNull($values, $replace = null)
    {
        foreach ($values as $key => $value) {
            if (!is_array($value) && !strlen($value)) {
                $values[$key] = $replace;
            }
        }
        return $values;
    }

    public static function transliterate($txt)
    {
        $transliterationTable = [
            'á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ă' => 'a', 'Ă' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ą' => 'a', 'Ą' => 'A', 'ā' => 'a', 'Ā' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ḃ' => 'b', 'Ḃ' => 'B', 'ć' => 'c', 'Ć' => 'C', 'ĉ' => 'c', 'Ĉ' => 'C', 'č' => 'c', 'Č' => 'C', 'ċ' => 'c', 'Ċ' => 'C', 'ç' => 'c', 'Ç' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ḋ' => 'd', 'Ḋ' => 'D', 'đ' => 'd', 'Đ' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ĕ' => 'e', 'Ĕ' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ė' => 'e', 'Ė' => 'E', 'ę' => 'e', 'Ę' => 'E', 'ē' => 'e', 'Ē' => 'E', 'ḟ' => 'f', 'Ḟ' => 'F', 'ƒ' => 'f', 'Ƒ' => 'F', 'ğ' => 'g', 'Ğ' => 'G', 'ĝ' => 'g', 'Ĝ' => 'G', 'ġ' => 'g', 'Ġ' => 'G', 'ģ' => 'g', 'Ģ' => 'G', 'ĥ' => 'h', 'Ĥ' => 'H', 'ħ' => 'h', 'Ħ' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i',
            'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ĩ' => 'i', 'Ĩ' => 'I', 'į' => 'i', 'Į' => 'I', 'ī' => 'i', 'Ī' => 'I', 'ĵ' => 'j', 'Ĵ' => 'J', 'ķ' => 'k', 'Ķ' => 'K', 'ĺ' => 'l', 'Ĺ' => 'L', 'ľ' => 'l', 'Ľ' => 'L', 'ļ' => 'l', 'Ļ' => 'L', 'ł' => 'l', 'Ł' => 'L', 'ṁ' => 'm', 'Ṁ' => 'M', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ņ' => 'n', 'Ņ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ő' => 'o', 'Ő' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ō' => 'o', 'Ō' => 'O', 'ơ' => 'o', 'Ơ' => 'O', 'ö' => 'oe', 'Ö' => 'OE', 'ṗ' => 'p', 'Ṗ' => 'P', 'ŕ' => 'r', 'Ŕ' => 'R', 'ř' => 'r', 'Ř' => 'R', 'ŗ' => 'r', 'Ŗ' => 'R', 'ś' => 's', 'Ś' => 'S', 'ŝ' => 's', 'Ŝ' => 'S', 'š' => 's', 'Š' => 'S', 'ṡ' => 's', 'Ṡ' => 'S', 'ş' => 's', 'Ş' => 'S', 'ș' => 's', 'Ș' => 'S', 'ß' => 'SS', 'ť' => 't', 'Ť' => 'T', 'ṫ' => 't', 'Ṫ' => 'T', 'ţ' => 't', 'Ţ' => 'T', 'ț' => 't', 'Ț' => 'T', 'ŧ' => 't', 'Ŧ' => 'T', 'ú' => 'u', 'Ú' => 'U',
            'ù' => 'u', 'Ù' => 'U', 'ŭ' => 'u', 'Ŭ' => 'U', 'û' => 'u', 'Û' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ű' => 'u', 'Ű' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'ų' => 'u', 'Ų' => 'U', 'ū' => 'u', 'Ū' => 'U', 'ư' => 'u', 'Ư' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ẃ' => 'w', 'Ẃ' => 'W', 'ẁ' => 'w', 'Ẁ' => 'W', 'ŵ' => 'w', 'Ŵ' => 'W', 'ẅ' => 'w', 'Ẅ' => 'W', 'ý' => 'y', 'Ý' => 'Y', 'ỳ' => 'y', 'Ỳ' => 'Y', 'ŷ' => 'y', 'Ŷ' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ź' => 'z', 'Ź' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'ż' => 'z', 'Ż' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', 'а' => 'a', 'А' => 'a', 'б' => 'b', 'Б' => 'b', 'в' => 'v', 'В' => 'v', 'г' => 'g', 'Г' => 'g', 'д' => 'd', 'Д' => 'd', 'е' => 'e', 'Е' => 'E', 'ё' => 'e', 'Ё' => 'E', 'ж' => 'zh', 'Ж' => 'zh', 'з' => 'z', 'З' => 'z', 'и' => 'i', 'И' => 'i', 'й' => 'j', 'Й' => 'j', 'к' => 'k', 'К' => 'k', 'л' => 'l', 'Л' => 'l', 'м' => 'm', 'М' => 'm', 'н' => 'n', 'Н' => 'n', 'о' => 'o', 'О' => 'o', 'п' => 'p', 'П' => 'p', 'р' => 'r', 'Р' => 'r',
            'с' => 's', 'С' => 's', 'т' => 't', 'Т' => 't', 'у' => 'u', 'У' => 'u', 'ф' => 'f', 'Ф' => 'f', 'х' => 'h', 'Х' => 'h', 'ц' => 'c', 'Ц' => 'c', 'ч' => 'ch', 'Ч' => 'ch', 'ш' => 'sh', 'Ш' => 'sh', 'щ' => 'sch', 'Щ' => 'sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'y', 'ь' => '', 'Ь' => '', 'э' => 'e', 'Э' => 'e', 'ю' => 'ju', 'Ю' => 'ju', 'я' => 'ja', 'Я' => 'ja',
        ];
        return str_replace(array_keys($transliterationTable), array_values($transliterationTable), $txt);
    }

    /**
     * Translates a number to a short alhanumeric version
     *
     * Translated any number up to 9007199254740992
     * to a shorter version in letters e.g.:
     * 9007199254740989 --> PpQXn7COf
     *
     * specifiying the second argument true, it will
     * translate back e.g.:
     * PpQXn7COf --> 9007199254740989
     *
     * this function is based on any2dec && dec2any by
     * fragmer[at]mail[dot]ru
     * see: http://nl3.php.net/manual/en/function.base-convert.php#52450
     *
     * If you want the alphaID to be at least 3 letter long, use the
     * $pad_up = 3 argument
     *
     * In most cases this is better than totally random ID generators
     * because this can easily avoid duplicate ID's.
     * For example if you correlate the alpha ID to an auto incrementing ID
     * in your database, you're done.
     *
     * The reverse is done because it makes it slightly more cryptic,
     * but it also makes it easier to spread lots of IDs in different
     * directories on your filesystem. Example:
     * $part1 = substr($alpha_id,0,1);
     * $part2 = substr($alpha_id,1,1);
     * $part3 = substr($alpha_id,2,strlen($alpha_id));
     * $destindir = "/".$part1."/".$part2."/".$part3;
     * // by reversing, directories are more evenly spread out. The
     * // first 26 directories already occupy 26 main levels
     *
     * more info on limitation:
     * - http://blade.nagaokaut.ac.jp/cgi-bin/scat.rb/ruby/ruby-talk/165372
     *
     * if you really need this for bigger numbers you probably have to look
     * at things like: http://theserverpages.com/php/manual/en/ref.bc.php
     * or: http://theserverpages.com/php/manual/en/ref.gmp.php
     * but I haven't really dugg into this. If you have more info on those
     * matters feel free to leave a comment.
     *
     * @param  mixed  $in  String or long input to translate
     * @param  boolean  $to_num  Reverses translation when true
     * @param  mixed  $pad_up  Number or boolean padds the result up to a specified length
     * @param  string  $passKey  Supplying a password makes it harder to calculate the original ID
     *
     * @return mixed string or long
     * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
     * @author  Simon Franz
     * @author  Deadfish
     * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
     * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
     * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
     * @link    http://kevin.vanzonneveld.net/
     *
     */
    function alphaID($in, $to_num = false, $pad_up = 3, $passKey = null)
    {
        $index = "b1c2d3f4g5h6j7k8l9m0npqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
        if ($passKey !== null) {
            // Although this function's purpose is to just make the
            // ID short - and not so much secure,
            // with this patch by Simon Franz (http://blog.snaky.org/)
            // you can optionally supply a password to make it harder
            // to calculate the corresponding numeric ID

            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }

            $passhash = hash('sha256', $passKey);
            $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512', $passKey) : $passhash;

            for ($n = 0; $n < strlen($index); $n++) {
                $p[] = substr($passhash, $n, 1);
            }

            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }

        $base = strlen($index);

        if ($to_num) {
            // Digital number  <<--  alphabet letter code
            $in = strrev($in);
            $out = 0;
            $len = strlen($in) - 1;
            for ($t = 0; $t <= $len; $t++) {
                $bcpow = bcpow($base, $len - $t);
                $out = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
            $out = sprintf('%F', $out);
            $out = substr($out, 0, strpos($out, '.'));
        } else {
            // Digital number  -->>  alphabet letter code
            if (is_numeric($pad_up)) {
                $pad_up--;
                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            $out = "";
            for ($t = floor(log($in, $base)); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in = $in - ($a * $bcp);
            }
            $out = strrev($out); // reverse
        }

        return $out;
    }

    function extractCommonWords($string)
    {
        $stopWords = [
            'i',
            'a',
            'about',
            'an',
            'and',
            'are',
            'as',
            'at',
            'be',
            'by',
            'com',
            'de',
            'en',
            'for',
            'from',
            'how',
            'in',
            'is',
            'it',
            'la',
            'of',
            'on',
            'or',
            'that',
            'the',
            'this',
            'to',
            'was',
            'what',
            'when',
            'where',
            'who',
            'will',
            'with',
            'und',
            'the',
            'www',
        ];

        //$string = preg_replace('/s\s+/i', '', $string);
        $string = trim($string ?? ''); // trim the string
        $string = preg_replace('/[^a-zA-Z0-9 -]/', '', $string); // only take alphanumerical characters, but keep the spaces and dashes too�
        $string = strtolower($string); // make it lowercase

        preg_match_all('/\b.*?\b/i', $string, $matchWords);
        $matchWords = $matchWords[0];

        foreach ($matchWords as $key => $item) {
            if ($item == '' || in_array(strtolower($item), $stopWords) || strlen($item) <= 3) {
                unset($matchWords[$key]);
            }
        }
        $wordCountArr = [];
        if (is_array($matchWords)) {
            foreach ($matchWords as $key => $val) {
                $val = strtolower($val);
                if (isset($wordCountArr[$val])) {
                    $wordCountArr[$val]++;
                } else {
                    $wordCountArr[$val] = 1;
                }
            }
        }
        arsort($wordCountArr);
        $wordCountArr = array_slice($wordCountArr, 0, 10);
        return $wordCountArr;
    }

    /*
     * Password generator
     * From http://www.dougv.com/blog/2010/03/23/a-strong-password-generator-written-in-php/
     */
    function generatePassword($l = 8, $c = 2, $n = 2, $s = 0)
    {
        // get count of all required minimum special chars
        $count = $c + $n + $s;

        // sanitize inputs; should be self-explanatory
        if (!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
            trigger_error('Argument(s) not an integer', E_USER_WARNING);
            return false;
        } elseif ($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
            trigger_error('Argument(s) out of range', E_USER_WARNING);
            return false;
        } elseif ($c > $l) {
            trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($n > $l) {
            trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($s > $l) {
            trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
            return false;
        } elseif ($count > $l) {
            trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
            return false;
        }

        // all inputs clean, proceed to build password

        // change these strings if you want to include or exclude possible password characters
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $caps = strtoupper($chars);
        $nums = "0123456789";
        $syms = "!@#$%^&*()-+?";

        // build the base password of all lower-case letters
        $out = "";
        for ($i = 0; $i < $l; $i++) {
            $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }

        // create arrays if special character(s) required
        if ($count) {
            // split base password to array; create special chars array
            $tmp1 = str_split($out);
            $tmp2 = [];

            // add required special character(s) to second array
            for ($i = 0; $i < $c; $i++) {
                array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
            }
            for ($i = 0; $i < $n; $i++) {
                array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
            }
            for ($i = 0; $i < $s; $i++) {
                array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
            }

            // hack off a chunk of the base password array that's as big as the special chars array
            $tmp1 = array_slice($tmp1, 0, $l - $count);
            // merge special character(s) array with base password array
            $tmp1 = array_merge($tmp1, $tmp2);
            // mix the characters up
            shuffle($tmp1);
            // convert to string for output
            $out = implode('', $tmp1);
        }

        return $out;
    }

    function nl2br_limit($string, $num)
    {

        $dirty = preg_replace('/\r/', '', $string);
        $clean = preg_replace('/\n{4,}/', str_repeat('<br/>', $num), preg_replace('/\r/', '', $dirty));

        return nl2br($clean);
    }

    public static function escape($str)
    {
        $str = htmlentities($str);
        return $str;
    }

    public static function combine($chunks, $glue = "\n")
    {
        $chunks = array_map('trim', $chunks ?: []);
        $chunks = array_filter($chunks, function ($chunk) {
            return strlen($chunk) > 0;
        });
        return implode($glue, $chunks);
    }

    public static function lines($string, $lineSize = 0)
    {
        $lines = preg_split("/\r\n|\n|\r/", trim($string ?? ''));

        if ($lineSize > 0) {
            $lines2 = [];
            foreach ($lines as $line) {
                $chunks = str_split($line, $lineSize);
                $lines2 = array_merge($lines2, $chunks);
            }
            $lines = $lines2;
        }

        return $lines;
    }

    public static function toHtml($str, $o = [])
    {
        $defaults = [
            'hyperlink' => true,
            'linkTarget' => '_blank',
        ];
        $o += $defaults;

        $str = trim($str ?? '');
        $str = static::escape($str);
        $str = nl2br($str);

        if ($o['hyperlink']) {
            $str = static::autolink($str, 60, " target='_blank'");
        }

        return $str;
    }

    public static function parseAlphaNumeric($text)
    {
        $prefix = preg_replace('/[^a-zA-Z]/', '', $text);
        $number = preg_replace('/[^0-9]/', '', $text);
        $padLength = strlen($number);

        return [
            'value' => $text,
            'prefix' => $prefix,
            'number' => intval($number),
            'padLength' => $padLength,
        ];
    }

    public static function autoIncrement($text, $o = [])
    {
        $parsed = static::parseAlphaNumeric($text);

        $nextNumber = $parsed['number'] + 1;
        $numberPadded = str_pad($nextNumber, $parsed['padLength'], '0', STR_PAD_LEFT);

        return Text::combine([
            $parsed['prefix'],
            $numberPadded,
        ]);
    }

    public static function autoSequence($text, $n = 1, $startFrom = null)
    {
        $seq = [];
        $parsed = static::parseAlphaNumeric($text);

        if (!$startFrom) {
            $startFrom = $parsed['number'] + 1;
        }

        $nextNumber = $startFrom;
        while (count($seq) < $n) {
            $numberPadded = str_pad($nextNumber, $parsed['padLength'], '0', STR_PAD_LEFT);

            $token = Text::combine([
                $parsed['prefix'],
                $numberPadded,
            ], '');

            $seq[] = $token;
            $nextNumber++;
        }

        return $seq;
    }

    /**
     * Takes an under_scored version of a word and turns it into an human- readable form
     * by replacing underscores with a space, and by upper casing the initial character.
     *
     * @param  string  $word  Under_scored version of a word (i.e. `'red_bike'`).
     * @param  string  $separator  The separator character used in the initial string.
     * @return string Human readable version of the word (i.e. `'Red Bike'`).
     */
    public static function humanize($word, $separator = '_')
    {
        if (isset(static::$_humanized[$key = $word . ':' . $separator])) {
            return static::$_humanized[$key];
        }
        return static::$_humanized[$key] = ucwords(str_replace($separator, " ", $word));
    }

    public static function sanitizeEmail($email)
    {
        return filter_var(trim($email ?? ''), FILTER_VALIDATE_EMAIL);
    }

    public static function autolink($text, $limit = 30, $tagfill = '', $auto_title = true)
    {

        $text = static::autolink_do($text, '![a-z][a-z-]+://!i', $limit, $tagfill, $auto_title);
        $text = static::autolink_do($text, '!(mailto|skype):!i', $limit, $tagfill, $auto_title);
        $text = static::autolink_do($text, '!www\\.!i', $limit, $tagfill, $auto_title, 'http://');
        return $text;
    }

    private static function autolink_do($text, $sub, $limit, $tagfill, $auto_title, $force_prefix = null)
    {

        $text_l = StrToLower($text);
        $cursor = 0;
        $loop = 1;
        $buffer = '';

        while (($cursor < strlen($text)) && $loop) {

            $ok = 1;
            $matched = preg_match($sub, $text_l, $m, PREG_OFFSET_CAPTURE, $cursor);

            if (!$matched) {

                $loop = 0;
                $ok = 0;
            } else {

                $pos = $m[0][1];
                $sub_len = strlen($m[0][0]);

                $pre_hit = substr($text, $cursor, $pos - $cursor);
                $hit = substr($text, $pos, $sub_len);
                $pre = substr($text, 0, $pos);
                $post = substr($text, $pos + $sub_len);

                $fail_text = $pre_hit . $hit;
                $fail_len = strlen($fail_text);

                #
                # substring found - first check to see if we're inside a link tag already...
                #

                $bits = preg_split("!</a>!i", $pre);
                $last_bit = array_pop($bits);
                if (preg_match("!<a\s!i", $last_bit)) {

                    #echo "fail 1 at $cursor<br />\n";

                    $ok = 0;
                    $cursor += $fail_len;
                    $buffer .= $fail_text;
                }
            }

            #
            # looks like a nice spot to autolink from - check the pre
            # to see if there was whitespace before this match
            #

            if ($ok) {

                if ($pre) {
                    if (!preg_match('![\s\(\[\{>]$!s', $pre)) {

                        #echo "fail 2 at $cursor ($pre)<br />\n";

                        $ok = 0;
                        $cursor += $fail_len;
                        $buffer .= $fail_text;
                    }
                }
            }

            #
            # we want to autolink here - find the extent of the url
            #

            if ($ok) {
                if (preg_match('/^([a-z0-9\-\.\/\-_%~!?=,:;&+*#@\(\)\$]+)/i', $post, $matches)) {

                    $url = $hit . $matches[1];

                    $cursor += strlen($url) + strlen($pre_hit);
                    $buffer .= $pre_hit;

                    $url = html_entity_decode($url);

                    #
                    # remove trailing punctuation from url
                    #

                    while (preg_match('|[.,!;:?]$|', $url)) {
                        $url = substr($url, 0, strlen($url) - 1);
                        $cursor--;
                    }
                    foreach (['()', '[]', '{}'] as $pair) {
                        $o = substr($pair, 0, 1);
                        $c = substr($pair, 1, 1);
                        if (preg_match("!^(\\$c|^)[^\\$o]+\\$c$!", $url)) {
                            $url = substr($url, 0, strlen($url) - 1);
                            $cursor--;
                        }
                    }

                    #
                    # nice-i-fy url here
                    #

                    $link_url = $url;
                    $display_url = $url;

                    if ($force_prefix) {
                        $link_url = $force_prefix . $link_url;
                    }

                    if ($GLOBALS['autolink_options']['strip_protocols']) {
                        if (preg_match('!^(http|https)://!i', $display_url, $m)) {

                            $display_url = substr($display_url, strlen($m[1]) + 3);
                        }
                    }

                    $display_url = static::autolink_label($display_url, $limit);

                    #
                    # add the url
                    #

                    if ($display_url != $link_url && !preg_match('@title=@msi', $tagfill) && $auto_title) {

                        $display_quoted = preg_quote($display_url, '!');

                        if (!preg_match("!^(http|https)://{$display_quoted}$!i", $link_url)) {

                            $tagfill .= ' title="' . $link_url . '"';
                        }
                    }

                    $link_url_enc = HtmlSpecialChars($link_url);
                    $display_url_enc = HtmlSpecialChars($display_url);

                    $buffer .= "<a href=\"{$link_url_enc}\"$tagfill>{$display_url_enc}</a>";
                } else {
                    #echo "fail 3 at $cursor<br />\n";

                    $ok = 0;
                    $cursor += $fail_len;
                    $buffer .= $fail_text;
                }
            }
        }

        #
        # add everything from the cursor to the end onto the buffer.
        #

        $buffer .= substr($text, $cursor);

        return $buffer;
    }

    private static function autolink_label($text, $limit)
    {

        if (!$limit) {
            return $text;
        }

        if (strlen($text) > $limit) {
            return substr($text, 0, $limit - 3) . '...';
        }

        return $text;
    }

    private static function autolink_email($text, $tagfill = '')
    {

        $atom = '[^()<>@,;:\\\\".\\[\\]\\x00-\\x20\\x7f]+'; # from RFC822

        #die($atom);

        $text_l = StrToLower($text);
        $cursor = 0;
        $loop = 1;
        $buffer = '';

        while (($cursor < strlen($text)) && $loop) {

            #
            # find an '@' symbol
            #

            $ok = 1;
            $pos = strpos($text_l, '@', $cursor);

            if ($pos === false) {

                $loop = 0;
                $ok = 0;
            } else {

                $pre = substr($text, $cursor, $pos - $cursor);
                $hit = substr($text, $pos, 1);
                $post = substr($text, $pos + 1);

                $fail_text = $pre . $hit;
                $fail_len = strlen($fail_text);

                #die("$pre::$hit::$post::$fail_text");

                #
                # substring found - first check to see if we're inside a link tag already...
                #

                $bits = preg_split("!</a>!i", $pre);
                $last_bit = array_pop($bits);
                if (preg_match("!<a\s!i", $last_bit)) {

                    #echo "fail 1 at $cursor<br />\n";

                    $ok = 0;
                    $cursor += $fail_len;
                    $buffer .= $fail_text;
                }
            }

            #
            # check backwards
            #

            if ($ok) {
                if (preg_match("!($atom(\.$atom)*)\$!", $pre, $matches)) {

                    # move matched part of address into $hit

                    $len = strlen($matches[1]);
                    $plen = strlen($pre);

                    $hit = substr($pre, $plen - $len) . $hit;
                    $pre = substr($pre, 0, $plen - $len);
                } else {

                    #echo "fail 2 at $cursor ($pre)<br />\n";

                    $ok = 0;
                    $cursor += $fail_len;
                    $buffer .= $fail_text;
                }
            }

            #
            # check forwards
            #

            if ($ok) {
                if (preg_match("!^($atom(\.$atom)*)!", $post, $matches)) {

                    # move matched part of address into $hit

                    $len = strlen($matches[1]);

                    $hit .= substr($post, 0, $len);
                    $post = substr($post, $len);
                } else {
                    #echo "fail 3 at $cursor ($post)<br />\n";

                    $ok = 0;
                    $cursor += $fail_len;
                    $buffer .= $fail_text;
                }
            }

            #
            # commit
            #

            if ($ok) {

                $cursor += strlen($pre) + strlen($hit);
                $buffer .= $pre;
                $buffer .= "<a href=\"mailto:$hit\"$tagfill>$hit</a>";
            }
        }

        #
        # add everything from the cursor to the end onto the buffer.
        #

        $buffer .= substr($text, $cursor);

        return $buffer;
    }

    public static function extractEmails($string)
    {
        $emails = [];

        foreach (preg_split('/\s/', $string) as $token) {
            $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
            if ($email !== false) {
                $emails[] = $email;
            }
        }

        return $emails;
    }
}
