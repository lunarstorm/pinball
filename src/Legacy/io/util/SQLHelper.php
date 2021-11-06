<?php

namespace Vio\Pinball\Legacy\io\util;

use Illuminate\Support\Facades\DB;

/**
 * A helper class containing useful utility functions for
 * use with building model queries, and any other SQL related
 * functions.
 *
 * @author Jasper Tey
 *
 */
class SQLHelper {

	/**
	 * Merges a series of conditions clauses into a single seamless
	 * conditions array.
	 *
	 * @param mixed $conditions The merged conditions array.
	 */
	public static function merge_conditions($conditions = []) {
		if (!$conditions) {
			return [];
		}

		$main = [];
		$tokens = [];

		foreach ($conditions as $clause) {
			if (is_array($clause)) {
				foreach ($clause as $i => $fragment) {
					if ($i == 0) {
						$main[] = $fragment;
					} else {
						$tokens[] = $fragment;
					}
				}
			} else {
				$main[] = $clause;
			}
		}

		$main = implode(' and ', $main);

		array_unshift($tokens, $main);

		return $tokens;
	}

	public static function prepare($query, $params) {
		return static::interpolate($query, $params);
	}

	public static function implode($values) {
		return implode(',', array_map(function ($value) {
			return static::quote($value);
		}, $values));
	}

    public static function quote($value){
        return DB::connection()->getPdo()->quote($value);
    }

	public static function interpolate($query, $params) {
		$keys = array();

		if (!is_array($params)) {
			$params = [$params];
		}

		# build a regular expression for each parameter
		foreach ($params as $key => $value) {
			$params[$key] = static::quote($value);

			if (is_string($key)) {
				$keys[] = '/:' . $key . '/';
			} else {
				$keys[] = '/[?]/';
			}
		}

		$query = preg_replace($keys, $params, $query, 1, $count);

		#trigger_error('replaced '.$count.' keys');

		return $query;
	}

}
