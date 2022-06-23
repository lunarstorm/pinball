<?php

namespace Vio\Pinball\Helpers;

class Date
{
    public static function format($format, $in = null)
    {
        $ts = null;

        if (! $in) {
            return '';
        }

        if ($in instanceof \DateTime) {
            $ts = $in->getTimestamp();
        } else {
            $ts = strtotime($in);
        }

        return date($format, $ts);
    }

    public static function diff($dateString1, $dateString2 = null)
    {
        $t1 = strtotime($dateString1);
        $t2 = $dateString2 ? strtotime($dateString2) : time();
        $d1 = new \DateTime("@{$t1}");
        $d2 = new \DateTime("@{$t2}");
        $diff = $d1->diff($d2);

        $diff = (array) $diff;

        $units = [
            'y' => 1,
            'm' => 1,
            'd' => 1,
            'h' => 1,
            'i' => 1,
            's' => 1,
            'days' => 1,
        ];

        $parsed = [];

        foreach ($units as $u => $factor) {
            $invert = $diff['invert'] ? 1 : -1;
            $parsed[$u] = $invert * $diff[$u];
        }

        // Todo: compute totals of each unit
        /*$total = [
            'years' => $diff['y']
        ];*/

        $parsed['raw'] = $diff;

        return $parsed;
    }

    public static function daysAfter($days, $date, $format = null)
    {
        if (! $date) {
            return false;
        }

        $dateCopy = clone $date;
        $dateCopy->add(new \DateInterval("P{$days}D"));

        if ($format) {
            return $dateCopy->format($format);
        }

        return $dateCopy;
    }

    public static function daysBefore($days, $date, $format = null)
    {
        if (! $date) {
            return false;
        }

        $dateCopy = clone $date;
        $dateCopy->sub(new \DateInterval("P{$days}D"));

        if ($format) {
            return $dateCopy->format($format);
        }

        return $dateCopy;
    }

    public static function weekCovering($date = 'today')
    {
        $ts = strtotime($date);

        $start = (date('w', $ts) == 1) ? $ts : strtotime('last monday', $ts);
        $start_date = date('Y-m-d', $start);

        $end = strtotime('next sunday', $start);
        $end_date = date('Y-m-d', $end);

        return [
            'startTime' => $start,
            'endTime' => $end,
            'start' => $start_date,
            'end' => $end_date,
        ];
    }

    public static function daysRemainingInMonth($ts = null)
    {
        if (is_null($ts)) {
            $ts = time();
        }
        $daysRemaining = (int) date('t', $ts) - (int) date('j', $ts);

        return $daysRemaining;
    }

    public static function daysBetween($date1, $date2)
    {
        return static::nightsBetween($date1, $date2) + 1;
    }

    public static function nightsBetween($date1, $date2)
    {
        $t1 = strtotime($date1);
        $t2 = strtotime($date2);
        $diff = abs($t2 - $t1);
        $days = floor($diff / (60 * 60 * 24));
        if ($days == 0) {
            $days = 1;
        }

        return $days;
    }

    public static function getTimeAgo($datetime, $units = 2, $suffix = ' ago')
    {
        if ($datetime instanceof \DateTime) {
            $time = $datetime->getTimestamp();
        } else {
            $time = strtotime($datetime);
        }

        if (! $time) {
            return '';
        }

        $timeAgo = Time::getTimeElapsed($time, $units);

        if (! $timeAgo) {
            return __('just now');
        }

        return __('{:time} ago', ['time' => $timeAgo]);
    }

    public static function getTimeTo($date, $units = 2, $suffix = '')
    {
        if ($date instanceof \DateTime) {
            $unixtime = $date->getTimestamp();
        } else {
            $unixtime = Time::mysqlToUnixTime($date);
        }
        $timeAgo = Time::getTimeElapsed($unixtime, $units, true);

        return $timeAgo.$suffix;
    }

    public static function daysOfWeekSymbols()
    {
        $days = ['Su', 'M', 'T', 'W', 'Th', 'F', 'S'];

        return $days;
    }

    public static function daySymbol($w)
    {
        $days = static::daysOfWeekSymbols();

        return $days[$w];
    }

    public static function years($from = '', $to = '')
    {
        if (empty($from)) {
            $from = date('Y');
        }
        if (empty($to)) {
            $to = $from + 20;
        }

        if ($from > $to) {
            $years = array_reverse(range($to, $from));
        } else {
            $years = range($from, $to);
        }

        return $years;
    }

    public static function monthsNumeric()
    {
        extract(Message::aliases());
        $months = [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
        ];

        return $months;
    }

    public static function monthDays()
    {
        $days = [
            '01' => '01',
            '02' => '02',
            '03' => '03',
            '04' => '04',
            '05' => '05',
            '06' => '06',
            '07' => '07',
            '08' => '08',
            '09' => '09',
            '10' => '10',
            '11' => '11',
            '12' => '12',
            '13' => '13',
            '14' => '14',
            '15' => '15',
            '16' => '16',
            '17' => '17',
            '18' => '18',
            '19' => '19',
            '20' => '20',
            '21' => '21',
            '22' => '22',
            '23' => '23',
            '24' => '24',
            '25' => '25',
            '26' => '26',
            '27' => '27',
            '28' => '28',
            '29' => '29',
            '30' => '30',
            '31' => '31',
        ];

        return $days;
    }

    public static function months()
    {
        $months = [
            [
                'text' => 'Jan',
                'value' => 1,
            ],
            [
                'text' => 'Feb',
                'value' => 2,
            ],
            [
                'text' => 'Mar',
                'value' => 3,
            ],
            [
                'text' => 'Apr',
                'value' => 4,
            ],
            [
                'text' => 'May',
                'value' => 5,
            ],
            [
                'text' => 'Jun',
                'value' => 6,
            ],
            [
                'text' => 'Jul',
                'value' => 7,
            ],
            [
                'text' => 'Aug',
                'value' => 8,
            ],
            [
                'text' => 'Sep',
                'value' => 9,
            ],
            [
                'text' => 'Oct',
                'value' => 10,
            ],
            [
                'text' => 'Nov',
                'value' => 11,
            ],
            [
                'text' => 'Dec',
                'value' => 12,
            ],
        ];

        return $months;
    }
}
