<?php

namespace Kir\Data\Arrays\Helpers;

use ArrayAccess;

class ArrayLocator {
	/**
	 * @param array $array
	 * @param string[] $path
	 * @return bool
	 */
	public static function has(array $array, array $path) {
		$count = count($path);
		if(!$count) {
			return false;
		}
		for($idx = 0; $idx < $count; $idx++) {
			$part = $path[$idx];
			if(!self::keyExists($array, $part)) {
				return false;
			}
			$array = $array[$part];
		}
		return true;
	}
	
	/**
	 * @template T of array|ArrayAccess
	 * @template D
	 * @param T $array
	 * @param string[] $path
	 * @param D $default
	 * @return mixed|D
	 */
	public static function get($array, array $path, $default = null) {
		$count = count($path);
		if(!$count) {
			return $default;
		}
		for($idx = 0; $idx < $count; $idx++) {
			$part = $path[$idx];
			if(!self::keyExists($array, $part)) {
				return $default;
			}
			$array = $array[$part];
		}
		return $array;
	}
	
	/**
	 * @template T of (array<mixed, mixed>|ArrayAccess<mixed, mixed>)
	 * @template D
	 * @param T $array
	 * @param string[] $path
	 * @param D $value
	 * @return T
	 */
	public static function set(array $array, array $path, $value) {
		if(!count($path)) {
			return $array;
		}
		$key = array_shift($path);
		if(!array_key_exists($key, $array)) {
			$data[$key] = [];
		}
		if(count($path)) {
			$innerArray = $array[$key];
			if(!is_array($innerArray)) {
				$innerArray = [];
			}
			$data[$key] = self::set($innerArray, $path, $value);
		} else {
			$data[$key] = $value;
		}
		/** @var T $data */
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
		while(count($path)) { // Only try this while a valid path is given
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
	
	/**
	 * @param array|ArrayAccess $array
	 * @param string $key
	 * @return bool
	 */
	private static function keyExists($array, $key) {
		if(!is_array($array) && !($array instanceof ArrayAccess)) {
			return false;
		}
		return array_key_exists($key, $array);
	}
}