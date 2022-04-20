<?php

namespace Vio\Pinball\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Vio\Pinball\Legacy\io\models\EntityKeyValueModel;

class IoValue extends EntityKeyValueModel
{
    use HasTimestamps;

    public static function get($key, $o = [], $init = false)
    {
        $o = static::_parseOptions($o);
        $o['valueField'] = 'value_string';
        return parent::get($key, $o, $init);
    }

    public static function set($key, $value, $o = [])
    {
        $o = static::_parseOptions($o);
        $o['valueField'] = 'value_string';
        return parent::set($key, $value, $o);
    }

    public static function getInt($key, $o = [], $init = false)
    {
        $o = static::_parseOptions($o);
        $o['valueField'] = 'value_int';
        return parent::get($key, $o, $init);
    }

    public static function setInt($key, $value, $o = [])
    {
        $o = static::_parseOptions($o);
        $o['valueField'] = 'value_int';
        return parent::set($key, $value, $o);
    }

    public static function getFloat($key, $o = [], $init = false)
    {
        $o = static::_parseOptions($o);
        $o['valueField'] = 'value_float';
        return parent::get($key, $o, $init);
    }

    public static function setFloat($key, $value, $o = [])
    {
        $o = static::_parseOptions($o);
        $o['valueField'] = 'value_float';
        return parent::set($key, $value, $o);
    }

    public static function getDate($key, $o = [], $init = false)
    {
        $o = static::_parseOptions($o);
        $o['valueField'] = 'value_date';
        return parent::get($key, $o, $init);
    }

    public static function setDate($key, $value, $o = [])
    {
        $o = static::_parseOptions($o);
        $o['valueField'] = 'value_date';
        return parent::set($key, $value, $o);
    }
}
