<?php

namespace io\data;

use io\util\SQLHelper;
use io\util\Text;

class EntityKeyValueModel extends KeyValueModel {

	public static function translate($o) {
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

	protected static function _parseOptions($o = []) {
		$defaults = [
			'obj' => null,
			'oid' => null,
			'valueField' => 'value',
		];

		$o = static::translate($o);
		$o += $defaults;

		return $o;
	}

	public static function load($key, $o = []) {
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

		if ($key !== '*') {
			$conds[] = ['`key` = ?', $key];
		}

		$conditions = SQLHelper::merge_conditions($conds);

		$row = static::find([
			'conditions' => $conditions,
		]);

		return $row ?: null;
	}

	public static function all($key, $o = []) {
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

	public static function get($key, $o = [], $init = false) {
		$o = static::_parseOptions($o);

		if ($row = static::load($key, $o)) {
			return $row->{$o['valueField']};
		}

		if (is_callable($init)) {
			return static::set($key, $init(), $o);
		}

		return false;
	}

	public static function getForUser($key, $o = [], $init = false) {
		return static::get(self::userKey($key), $o, $init);
	}

	public static function set($key, $value, $o = []) {
		$o = static::_parseOptions($o);
		$row = static::load($key, $o);

		if (!$row) {
			$row = static::create([
				'obj' => $o['obj'],
				'oid' => $o['oid'],
				'key' => $key,
			]);
		}

		$row->update_attribute($o['valueField'], $value);

		return $row->{$o['valueField']};
	}

	public static function setForUser($key, $value, $o = []) {
		return static::set(self::userKey($key), $value, $o);
	}

}