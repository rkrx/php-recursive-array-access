<?php

namespace raa;

use RuntimeException;
use stdClass;

class RArray {
	/**
	 * @template T of array|object
	 * @param T $structure
	 * @param string[]|string $path
	 * @return bool
	 */
	public static function has($structure, $path): bool {
		if(is_string($path)) {
			return self::has($structure, self::getArrayPath($path));
		}
		$count = count($path);
		if($count) {
			$part = strtr(array_shift($path), ['\\.' => '.']);
			if(is_array($structure)) {
				if(array_key_exists($part, $structure)) {
					if(count($path)) {
						return self::has($structure[$part], $path);
					}
					return true;
				}
			} elseif(is_object($structure)) {
				if(property_exists($structure, $part)) {
					if(count($path)) {
						return self::has($structure->{$part}, $path);
					}
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * @template T of object|array
	 * @param T $structure
	 * @param string[]|string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get($structure, $path, $default = null) {
		if(is_string($path)) {
			return self::get($structure, self::getArrayPath($path), $default);
		}
		$count = count($path);
		if($count) {
			$part = strtr(array_shift($path), ['\\.' => '.']);
			if(is_array($structure)) {
				if(array_key_exists($part, $structure)) {
					if(count($path)) {
						/** @var array<mixed, mixed>|object $innerStructure */
						$innerStructure = $structure[$part];
						return self::get($innerStructure, $path, $default);
					}
					return $structure[$part];
				}
			} elseif(is_object($structure)) {
				if(property_exists($structure, $part)) {
					if(count($path)) {
						/** @var array<mixed, mixed>|object $innerStructure */
						$innerStructure = $structure->{$part};
						return self::get($innerStructure, $path, $default);
					}
					return $structure->{$part};
				}
			} else {
				throw new RuntimeException("Invalid type: " . gettype($structure));
			}
		}
		return $default;
	}

	/**
	 * @template T of object|array
	 * @param T $structure
	 * @param string[]|string $path
	 * @param mixed $value
	 * @return T
	 */
	public static function set($structure, $path, $value) {
		if(is_string($path)) {
			return self::set($structure, self::getArrayPath($path), $value);
		}
		$count = count($path);
		if($count) {
			$part = strtr(array_shift($path), ['\\.' => '.']);
			if(is_object($structure)) {
				if(count($path)) {
					if(property_exists($structure, $part)) {
						/** @var array<mixed, mixed>|object $innerStructure */
						$innerStructure = $structure->{$part};
						$structure->{$part} = self::set($innerStructure, $path, $value);
					} else {
						$structure->{$part} = self::set(new stdClass(), $path, $value);
					}
				} else {
					$structure->{$part} = $value;
				}
			} else {
				if(!is_array($structure)) {
					$structure = [];
				}
				if(count($path)) {
					if(array_key_exists($part, $structure)) {
						/** @var array<mixed, mixed>|object $innerStructure */
						$innerStructure = $structure[$part];
						$structure[$part] = self::set($innerStructure, $path, $value);
					} else {
						$structure[$part] = self::set([], $path, $value);
					}
				} else {
					$structure[$part] = $value;
				}
			}
		}
		/** @var T $structure */
		return $structure;
	}

	/**
	 * @template T of object|array
	 * @param T $structure
	 * @param string[]|string $path
	 * @return T
	 */
	public static function rem($structure, $path) {
		if(is_string($path)) {
			return self::rem($structure, self::getArrayPath($path));
		}
		$count = count($path);
		if($count) {
			$part = strtr(array_shift($path), ['\\.' => '.']);
			if(is_object($structure)) {
				if(count($path)) {
					if(property_exists($structure, $part)) {
						/** @var array<mixed, mixed>|object $innerStructure */
						$innerStructure = $structure->{$part};
						$structure->{$part} = self::rem($innerStructure, $path);
					}
				} else {
					unset($structure->{$part});
				}
			} else {
				if(!is_array($structure)) {
					$structure = [];
				}
				if(count($path)) {
					if(array_key_exists($part, $structure)) {
						/** @var array<mixed, mixed>|object $innerStructure */
						$innerStructure = $structure[$part];
						$structure[$part] = self::rem($innerStructure, $path);
					}
				} else {
					unset($structure[$part]);
				}
			}
		}
		/** @var T $structure */
		return $structure;
	}

	/**
	 * @param string $path
	 * @return string[]
	 */
	private static function getArrayPath(string $path): array {
		$strPath = preg_split('{(?<!\\\\)(?:\\\\\\\\)*\\.}', $path);
		if($strPath === false) {
			throw new RuntimeException();
		}
		return $strPath;
	}
}

