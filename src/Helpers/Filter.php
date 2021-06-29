<?php

namespace Vio\Pinball\Helpers;

class Filter
{

    public static function params($defaults = [], $params = [])
    {
        $params = array_filter($params);
        $params += $defaults;
        $params = array_intersect_key($params, $defaults);
        return $params;
    }

    public static function value($in, $o = [])
    {
        $out = trim($in);
        return $out;
    }

    public static function strval($in, $o = [])
    {
        return strval(static::value($in, $o));
    }

    public static function intval($in, $o = [])
    {
        return intval(static::value($in, $o));
    }

    public static function multiValue($in, $o = [])
    {
        return static::explode($in, $o);
    }

    public static function explode($in, $o = [])
    {
        $defaults = [
            'exclude' => [],
        ];
        $o += $defaults;

        if (!is_array($o['exclude'])) {
            $o['exclude'] = [$o['exclude']];
        }

        if (!is_array($in)) {
            $in = [$in];
        }

        $out = array_filter($in, function ($value) {
            return ($value !== null && $value !== false && $value !== '');
        });

        if ($exclude = $o['exclude']) {
            foreach ($out as $i => $val) {
                if (in_array($val, $exclude)) {
                    unset($out[$i]);
                }
            }
        }

        return $out;
    }

    public static function arrayToOptions($in, $o = [])
    {
        $defaults = [
            'keyLabel' => 'text',
            'keyValue' => 'value',
            'exclude' => [],
            'extra' => [],
        ];
        $o += $defaults;

        if ($extra = $o['extra']) {
            foreach ($extra as $key => $value) {
                $in[$key] = $value;
            }
        }

        $options = [];
        foreach ($in as $key => $value) {
            if ($o['exclude'] && in_array($key, $o['exclude'])) {
                continue;
            }

            $options[] = [
                $o['keyLabel'] => strval($value),
                $o['keyValue'] => strval($value),
            ];
        }

        return $options;
    }

    public static function hashToOptions($in, $o = [])
    {
        $defaults = [
            'value' => 'text',
            'key' => 'value',
            'exclude' => [],
            'extra' => [],
        ];
        $o += $defaults;

        if ($extra = $o['extra']) {
            foreach ($extra as $key => $value) {
                $in[$key] = $value;
            }
        }

        $options = [];
        foreach ((array) $in as $key => $value) {
            if ($o['exclude'] && in_array($key, $o['exclude'])) {
                continue;
            }

            $options[] = [
                $o['value'] => strval($value),
                $o['key'] => strval($key),
            ];
        }

        return $options;
    }

    public static function export($o = [])
    {
        $defaults = [
            'baseUrl' => $_SERVER['REQUEST_URI'],
            'defaults' => [],
            'params' => [],
            'ignore' => [],
            'options' => [],
        ];
        $o += $defaults;

        foreach ($o['defaults'] as $key => $val) {
            if (!is_array($val)) {
                $o['defaults'][$key] = strval($val);
            }
        }

        $o['params'] = static::params($o['defaults'], $o['params']);

        return $o;
    }
}
