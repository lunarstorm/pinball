<?php

namespace Vio\Pinball\Legacy\io\models;

use Vio\Pinball\Legacy\io\util\SQLHelper;
use Vio\Pinball\Helpers\Text;

class EntityKeyValueModel extends KeyValueModel
{
    public static function translate($o)
    {
        $defaults = [
            'obj' => null,
            'oid' => null,
        ];

        if (!is_array($o)) {
            if (is_string($o)) {
                $o = [
                    'obj' => $o,
                    'oid' => 0,
                ];
            } elseif ($o && isset($o->id)) {
                $model = $o;
                $reflect = new \ReflectionClass($model);
                $o = [
                    'obj' => $reflect->getShortName(),
                    'oid' => $model->id,
                ];
            }
        } else {
            if (isset($o[0]) && isset($o[1])) {
                $o['obj'] = $o[0];
                $o['oid'] = $o[1];
                unset($o[0]);
                unset($o[1]);
            }
        }

        $o += $defaults;

        return $o;
    }

    protected static function _parseOptions($o = [])
    {
        $defaults = [
            'obj' => null,
            'oid' => null,
            'valueField' => 'value',
        ];

        $o = static::translate($o);
        $o += $defaults;

        return $o;
    }

    public static function _load($key, $o = [])
    {
        $o = static::_parseOptions($o);

        $conds = [];

        $qry = static::query();

        if (is_null($o['obj'])) {
            $qry->whereNull('obj');
        } else {
            $qry->whereObj($o['obj']);
        }

        if (is_null($o['oid'])) {
            $qry->whereNull('oid');
        } else {
            $qry->whereOid($o['oid']);
        }

        if ($key !== '*') {
            $qry->where('key', '=', $key);
        }

        return $qry->first();
    }

    public static function _all($key, $o = [])
    {
        $o = static::_parseOptions($o);

        $conds = [];

        if (is_null($o['obj'])) {
            $conds[] = ["obj is null"];
        } else {
            $conds[] = ["obj = ?", $o['obj']];
        }

        if (is_null($o['oid'])) {
            $conds[] = ["oid is null"];
        } else {
            $conds[] = ["oid = ?", $o['oid']];
        }

        if (Text::endsWith('*', $key)) {
            $key = rtrim($key, '*');
            $conds[] = ["`key` LIKE '{$key}%'"];
        } else {
            $conds[] = ['`key` = ?', $key];
        }

        $conditions = SQLHelper::merge_conditions($conds);
        $rows = parent::all([
            'conditions' => $conditions,
            'order' => '`key` ASC',
        ]);

        return $rows ?: [];
    }

    public static function get($key, $o = [], $init = false)
    {
        $o = static::_parseOptions($o);

        if ($row = static::_load($key, $o)) {
            $col = data_get($o, 'valueField');
            return data_get($row, $col);
        }

        if (is_callable($init)) {
            return static::set($key, $init(), $o);
        }

        return false;
    }

    public static function getForUser($key, $o = [], $init = false)
    {
        return static::get(self::userKey($key), $o, $init);
    }

    public static function set($key, $value, $o = [])
    {
        $o = static::_parseOptions($o);
        $row = static::_load($key, $o);

        if (!$row) {
            $row = static::create([
                'obj' => $o['obj'],
                'oid' => $o['oid'],
                'key' => $key,
            ]);
        }

        $row->updateQuietly([
            $o['valueField'] => $value
        ]);

        return $row->{$o['valueField']};
    }

    public static function setForUser($key, $value, $o = [])
    {
        return static::set(self::userKey($key), $value, $o);
    }
}
