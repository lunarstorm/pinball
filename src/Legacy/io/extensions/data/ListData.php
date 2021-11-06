<?php

namespace Vio\Pinball\Legacy\io\extensions\data;

use Illuminate\Support\Str;
use Vio\Pinball\Models\IoData;

class ListData
{
    protected static $_data = [];
    protected static $_cache = [];

    public static function set($name, $data)
    {
        if (is_callable($data)) {
            $data = $data();
        }

        static::$_data[$name] = $data;

        return true;
    }

    public static function get($path)
    {
        if ($cached = static::$_cache[$path]) {
            return $cached;
        }

        $value = IoData::get("lists.{$path}") ?: [];
        static::$_cache[$path] = $value;

        return $value;
    }

    public static function random($key)
    {
        if (!isset(static::$_data[$key])) {
            $key = Str::plural($key);
        }

        if ($data = static::get($key)) {
            $i = array_rand($data);
            $item = $data[$i];
            return $item;
        }

        return false;
    }
}
