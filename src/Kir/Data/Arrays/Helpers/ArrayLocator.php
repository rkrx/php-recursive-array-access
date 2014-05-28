<?php
namespace Kir\Data\Arrays\Helpers;

class ArrayLocator {
	/**
	 * @param array $array
	 * @param string[] $path
	 * @return bool
	 */
	public static function has(array $array, array $path) {
		if (!count($path)) {
			return false;
		}
		$value = $array;
		while (count($path)) {
			$key = array_shift($path);
			if (!(is_array($value) && array_key_exists($key, $value))) {
				return false;
			}
			$value = $value[$key];
		}
		return true;
	}

	/**
	 * @param array $array
	 * @param string[] $path
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get(array $array, array $path, $default = null) {
		$key = array_shift($path);
		$value = null;
		if (array_key_exists($key, $array)) {
			$value = $array[$key];
			if (count($path)) {
				if (is_array($value)) {
					$value = self::get($value, $path);
				} else {
					return null;
				}
			}
		}
		return $value;
	}

	/**
	 * @param array $array
	 * @param string[] $path
	 * @param mixed $value
	 * @return array
	 */
	public static function set(array $array, array $path, $value) {
		$key = array_shift($path);
		if (!array_key_exists($key, $array)) {
			$data[$key] = array();
		}
		if (count($path)) {
			$data[$key] = self::set($array[$key], $path, $value);
		} else {
			$data[$key] = $value;
		}
		return $data;
	}

	/**
	 * Run into each level of recursion until the last key was found, or until a key was missing at any level.
	 * If the last key was found, it gets removed by unset()
	 *
	 * @param array $array
	 * @param string[] $path
	 * @return array
	 */
	public static function remove(array $array, array $path) {
		while (count($path)) { // Only try this while a valid path is given
			$key = array_shift($path); // Get the current key
			if(array_key_exists($key, $array)) { // If the current key is present in the current recursion-level...
				if(count($path)) { // After involving array_shift, the path could now be empty. If not...
					if(is_array($array[$key])) { // If it is an array we need to step into the next recursion-level...
						$array[$key] = self::remove($array[$key], $path);
					}
					// If it is not an array, the sub-path can't be reached - stop.
				} else { // We finally arrived at the targeted node. Remove...
					unset($array[$key]);
				}
				break;
			} else { // If not, the path is not fully present in the array - stop.
				break;
			}
		}
		return $array;
	}
}