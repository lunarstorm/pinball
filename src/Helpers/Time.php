<?php

namespace Vio\Pinball\Helpers;

class Time
{
    ///////////////////////////////////////////////////////////
    // mysql_to_unix_time: converts a 14-digit MySQL //////////
    // timestamp to a UNIX timestamp //////////////////////////
    ///////////////////////////////////////////////////////////
    /*
     $datetime should be a number in the format yyyymmddhhmmss
     Returns the date's UNIX timestamp
    */
    public static function unixtimeToMysqlDatetime($unixtime = null)
    {
        if (!$unixtime) {
            $unixtime = time();
        }
        return date("Y-m-d H:i:s", $unixtime);
    }

    public static function dateFormat($format, $mysqltimestamp)
    {
        return date($format, Time::mysqlToUnixTime($mysqltimestamp));
    }

    public static function mysqlToUnixTime($datetime)
    {
        // Convert to string so we can reliably parse it
        settype($datetime, 'string');

        // Break the number up into its components (yyyymmddhhmmss)
        // storing results in the array matches
        preg_match('/(....)-(..)-(..) (..):(..):(..)/', $datetime, $matches);

        if ($matches == null) {
            return null;
        }

        // Pop the first element off the matches array.  The first
        // element is not a match, but the original string, which
        // we don't want.
        array_shift($matches);

        // Transfer the values in $matches into labeled variables
        $hour = $minute = $second = $month = $day = $year = "";
        foreach ([
            'year',
            'month',
            'day',
            'hour',
            'minute',
            'second',
        ] as $var) {
            $$var = array_shift($matches);
        }

        return mktime($hour, $minute, $second, $month, $day, $year);
    }

    public static function getTimeAgoStamp1($secondsAgo)
    {
        $minutesAgo = $secondsAgo / 60;
        $hoursAgo = $minutesAgo / 60;
        $daysAgo = $hoursAgo / 24;

        $stamp = "";
        if ($hoursAgo >= 24) {
            $stamp .= $daysAgo . " days";
        }
        return $stamp;
    }

    public static function getTimeElapsed($iDate, $iMaxUnits = 2, $allowFutureDates = false)
    {
        $sTimeElapsed = '';
        $aTimeBlocks = [
            31536000 => __('year'),
            2592000 => __('month'),
            86400 => __('day'),
            3600 => __('hour'),
            60 => __('minute'),
            1 => __('second'),
        ];

        $aTimeBlocksPlural = [
            31536000 => __('years'),
            2592000 => __('months'),
            86400 => __('days'),
            3600 => __('hours'),
            60 => __('minutes'),
            1 => __('seconds'),
        ];

        $timeNow = time();
        $iDiff = $timeNow - $iDate;

        if ($allowFutureDates) {
            $iDiff = abs($iDiff);
        }

        /*$dateNow = date(IO_DATE_LONGTIME, $timeNow);
        $dateTouch = date(IO_DATE_LONGTIME, $iDate);

        print_pre($dateNow);
        print_pre($dateTouch);*/

        $iUnitCount = 0;
        foreach ($aTimeBlocks as $iValue => $sLabel) {
            if ($iUnitCount >= $iMaxUnits) {
                break;
            }
            $iNumTimes = floor($iDiff / $iValue);
            if ($iNumTimes > 0) {
                if ($iNumTimes > 1) {
                    $sLabelPlural = $aTimeBlocksPlural[$iValue];
                    $sTimeElapsed .= "{$iNumTimes} {$sLabelPlural}, ";
                } else {
                    $sTimeElapsed .= "1 {$sLabel}, ";
                }
                $iDiff -= ($iNumTimes * $iValue);
                $iUnitCount++;
            }
        }
        return rtrim($sTimeElapsed, ', ');
    }

    public static function getTimeElapsed_($iDate, $iMaxUnits = 2)
    {
        $sTimeElapsed = '';
        $aTimeBlocks = [
            31536000 => 'year',
            2592000 => 'month',
            86400 => 'day',
            3600 => 'hour',
            60 => 'min',
            1 => 'second',
        ];
        $iDiff = time() - $iDate;
        $iUnitCount = 0;
        foreach ($aTimeBlocks as $iValue => $sLabel) {
            if ($iUnitCount >= $iMaxUnits) {
                break;
            }
            $iNumTimes = floor($iDiff / $iValue);
            if ($iNumTimes > 0) {
                $iNumTimes == 1 ? $sTimeElapsed .= "1 $sLabel, " : $sTimeElapsed .= "$iNumTimes {$sLabel}s, ";
                $iDiff -= ($iNumTimes * $iValue);
                $iUnitCount++;
            }
        }
        return rtrim($sTimeElapsed, ', ');
    }

    public static function time_ago($date, $granularity = 2)
    {
        $date = strtotime($date);
        $difference = time() - $date;
        $periods = [
            'decade' => 315360000,
            'year' => 31536000,
            'month' => 2628000,
            'week' => 604800,
            'day' => 86400,
            'hour' => 3600,
            'minute' => 60,
            'second' => 1,
        ];

        $retval = '';

        foreach ($periods as $key => $value) {
            if ($difference >= $value) {
                $time = floor($difference / $value);
                $difference %= $value;
                $retval .= ($retval ? ' ' : '') . $time . ' ';
                $retval .= (($time > 1) ? $key . 's' : $key);
                $granularity--;
            }
            if ($granularity == '0') {
                break;
            }
        }
        return ' posted ' . $retval . ' ago';
    }

    public static function getTimeAgoStamp($timestamp)
    {
        $difference = time() - $timestamp;
        $periods = [
            "second",
            "minute",
            "hour",
            "day",
            "week",
            "month",
            "year",
            "decade",
        ];
        $lengths = [
            "60",
            "60",
            "24",
            "7",
            "4.35",
            "12",
            "10",
        ];

        for ($j = 0; $difference >= $lengths[$j] && $j < 7; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= "s";
        }
        $text = "$difference $periods[$j] ago";
        return $text;
    }

    public static function get24to12hour($hour)
    {
        if ($hour > 0 && $hour < 13) {
            return $hour;
        }
        return $hour % 12 ?: 12;
    }

    public static function get12to24hour($hour, $meridian)
    {
        $meridian = strtolower($meridian);
        if ($meridian == "am") {
            $hour24 = $hour == 12 ? 0 : $hour;
        } else {
            $hour24 = $hour == 12 ? 12 : $hour + 12;
        }
        return $hour24;
    }

    public static function isAm($hour)
    {
        return $hour - 12 >= 0 ? false : true;
    }

    public static function options($o = [])
    {
        $defaults = [
            'firstHour' => 0,
            'stepHours' => 1,
            'stepMinutes' => 5,
        ];
        $o += $defaults;

        $options = [];
        $hours = range(0, 23, $o['stepHours']);
        $mins = $o['stepMinutes'] ? range(0, 59, $o['stepMinutes']) : [0];

        foreach ($hours as $h) {
            $h12 = static::get24to12hour($h);
            $M = static::isAm($h) ? 'AM' : 'PM';

            foreach ($mins as $m) {
                $m = str_pad($m, 2, '0', STR_PAD_LEFT);

                $value = "{$h}:{$m}:00";
                $time = "{$h12}:{$m} {$M}";

                $options[] = [
                    'text' => $time,
                    'value' => $value,
                ];
            }
        }

        return $options;
    }

    public static function format($time, $format = IO_DATE_TIMEONLY)
    {
        if (!$time) {
            return '';
        }

        $ts = strtotime($time);
        return date($format, $ts);
    }

    public static function hoursToParts($hours)
    {
        $fraction = Numeric::fraction($hours);

        $parts = [
            'hours' => $fraction['whole'],
            'minutes' => round(60 * $fraction['fraction']),
        ];

        return $parts;
    }

    public static function partsToHours($parts = [])
    {
        $defaults = [
            'hours' => 0,
            'minutes' => 0,
        ];
        $parts += $defaults;

        $hours = 0;
        $hours += $parts['hours'];
        $hours += ($parts['minutes'] / 60);

        return $hours;
    }
}
