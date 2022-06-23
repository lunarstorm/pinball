<?php

namespace Vio\Pinball\Legacy\io\extensions\data;

use Vio\Pinball\Models\IoData;

class SettingsArray
{
    public static $_prefix = '';

    protected static $_defaults = [];

    protected $_cfg = [];

    protected $_data = [];

    public function __construct($prefix = '', $o = [])
    {
        if (is_array($prefix)) {
            $o = $prefix;
        } else {
            $o['prefix'] = $prefix;
        }

        if (strlen(static::$_prefix)) {
            $o['prefix'] = static::$_prefix;
        }

        $defaults = [
            'obj' => null,
            'oid' => null,
            'prefix' => '',
            'defaults' => static::defaults(),
            'preload' => true,
        ];
        $o += $defaults;

        $this->_cfg = $o;

        if ($this->_cfg['preload']) {
            $this->reload();
        }
    }

    public static function defaults($params = null)
    {
        if (is_array($params)) {
            static::$_defaults = $params;
        }

        return static::$_defaults;
    }

    public function setData($data)
    {
        $data = array_intersect_key($data, static::defaults());
        $this->_data = $data;

        return $this->_data;
    }

    protected function _key()
    {
        return static::$_prefix;
    }

    public function save()
    {
        if ($data = $this->data()) {
            IoData::set($this->_key(), $data);
        }

        return true;
    }

    public function reload()
    {
        $prefix = addslashes($this->_cfg['prefix']);
        $data = IoData::get($prefix) ?: [];
        $this->setData($data);

        return $this->data();
    }

    public function data()
    {
        $this->_data += static::defaults();

        return $this->_data;
    }

    public function export()
    {
        return $this->data();
    }

    public static function get()
    {
        $settings = new static();

        return $settings->export();
    }
}
