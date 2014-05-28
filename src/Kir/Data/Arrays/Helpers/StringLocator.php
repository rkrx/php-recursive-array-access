<?php
namespace Kir\Data\Arrays\Helpers;

class StringLocator {
	/**
	 * @param array $array
	 * @param string $path
	 * @param string $separator
	 * @param string $escapeBy
	 * @return bool
	 */
	public static function has(array $array, $path, $separator = '.', $escapeBy = '\\') {
		$arrayPath = self::convert($path, $separator, $escapeBy);
		return ArrayLocator::has($array, $arrayPath);
	}

	/**
	 * @param array $array
	 * @param string[] $path
	 * @param mixed $default
	 * @param string $separator
	 * @param string $escapeBy
	 * @return mixed
	 */
	public static function get(array $array, array $path, $default = null, $separator = '.', $escapeBy = '\\') {
		$arrayPath = self::convert($path, $separator, $escapeBy);
		return ArrayLocator::get($array, $arrayPath, $default);
	}

	/**
	 * @param array $array
	 * @param string[] $path
	 * @param mixed $value
	 * @param string $separator
	 * @param string $escapeBy
	 * @return array
	 */
	public static function set(array $array, array $path, $value, $separator = '.', $escapeBy = '\\') {
		$arrayPath = self::convert($path, $separator, $escapeBy);
		return ArrayLocator::set($array, $arrayPath, $value);
	}

	/**
	 * @param array $array
	 * @param string[] $path
	 * @param string $separator
	 * @param string $escapeBy
	 * @return array
	 */
	public static function remove(array $array, array $path, $separator = '.', $escapeBy = '\\') {
		$arrayPath = self::convert($path, $separator, $escapeBy);
		return ArrayLocator::remove($array, $arrayPath);
	}

	/**
	 * @param string $path
	 * @param string $separator
	 * @param string $escapeBy
	 * @return array
	 */
	private static function convert($path, $separator, $escapeBy) {
		return StringPathToArrayPathConverter::convert($path, $separator, $escapeBy);
	}
}