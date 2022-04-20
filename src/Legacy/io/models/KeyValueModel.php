<?php

namespace Vio\Pinball\Legacy\io\models;

use Illuminate\Database\Eloquent\Model;
use lithium\security\Auth;

class KeyValueModel extends Model
{
    protected $guarded = [];

    public function set_value($in)
    {
        $this->assign_attribute('value', static::encode($in));
    }

    public function get_value()
    {
        $value = $this->read_attribute('value');
        return static::decode($value);
    }

    public static function read($value)
    {
        return static::decode($value);
    }

    public static function encode($in)
    {
        return json_encode($in);
    }

    public static function decode($in)
    {
        return json_decode($in, true);
    }

    public static function get($key, $o = [])
    {
        if ($row = static::_load($key, $o)) {
            return $row->value;
        }
        return false;
    }

    public static function _load($key, $o = [])
    {
        $o = static::_parseOptions($o);

        if ($key == '*' || $key === true) {
            $conditions = [
                'user_id = ?',
                $o['user_id'],
            ];

            $rows = static::all([
                'conditions' => $conditions,
                'order' => '`key` asc'
            ]);

            return $rows;
        }

        $conditions = [
            'user_id = ? and `key` = ?',
            $o['user_id'], $key
        ];

        $row = static::find([
            'conditions' => $conditions
        ]);

        return $row ?: null;
    }

    protected static function _parseOptions($o = [])
    {
        $user = auth()->user();

        $defaults = [
            'user_id' => $user->id,
        ];

        if (!is_array($o)) {
            if (is_numeric($o)) {
                $uid = $o;
            }
            if ($o && isset($o->id) && is_numeric($o->id)) {
                $uid = $o->id;
            }
            $o = [
                'user_id' => $uid,
            ];
        }

        $o += $defaults;
        return $o;
    }

    public static function userKey($key)
    {
        $user = auth()->user();

        $key = "user.{$user->id}.{$key}";
        return $key;
    }
}
