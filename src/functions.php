<?php
namespace raa;

use RuntimeException;
use stdClass;

/**
 * @param object|array $structure
 * @param array|string $path
 * @return bool
 */
function has($structure, $path) {
	if(is_string($path)) {
		return has($structure, preg_split('/(?<!\\\\)(?:\\\\\\\\)*\\./', $path));
	}
	$count = count($path);
	if($count) {
		$part = strtr(array_shift($path), ['\\.' => '.']);
		if(is_array($structure)) {
			if(array_key_exists($part, $structure)) {
				if(count($path)) {
					return has($structure[$part], $path);
				}
				return true;
			}
		} elseif(is_object($structure)) {
			if(property_exists($structure, $part)) {
				if(count($path)) {
					return has($structure->{$part}, $path);
				}
				return true;
			}
		} else {
			throw new RuntimeException("Invalid type: " . gettype($structure));
		}
	}
	return false;
}

/**
 * @param object|array $structure
 * @param array|string $path
 * @param mixed $default
 * @return mixed
 */
function get($structure, $path, $default = null) {
	if(is_string($path)) {
		return get($structure, preg_split('/(?<!\\\\)(?:\\\\\\\\)*\\./', $path), $default);
	}
	$count = count($path);
	if($count) {
		$part = strtr(array_shift($path), ['\\.' => '.']);
		if(is_array($structure)) {
			if(array_key_exists($part, $structure)) {
				if(count($path)) {
					return get($structure[$part], $path, $default);
				}
				return $structure[$part];
			}
		} elseif(is_object($structure)) {
			if(property_exists($structure, $part)) {
				if(count($path)) {
					return get($structure->{$part}, $path, $default);
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
 * @param object|array $structure
 * @param array|string $path
 * @param mixed $value
 * @return mixed
 */
function set($structure, $path, $value) {
	if(is_string($path)) {
		return set($structure, preg_split('/(?<!\\\\)(?:\\\\\\\\)*\\./', $path), $value);
	}
	$count = count($path);
	if($count) {
		$part = strtr(array_shift($path), ['\\.' => '.']);
		if(is_object($structure)) {
			if(count($path)) {
				if(property_exists($structure, $part)) {
					$structure->{$part} = set($structure->{$part}, $path, $value);
				} else {
					$structure->{$part} = set(new stdClass(), $path, $value);
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
					$structure[$part] = set($structure[$part], $path, $value);
				} else {
					$structure[$part] = set([], $path, $value);
				}
			} else {
				$structure[$part] = $value;
			}
		}
	}
	return $structure;
}

/**
 * @param object|array $structure
 * @param array|string $path
 * @return mixed
 */
function rem($structure, $path) {
	if(is_string($path)) {
		return rem($structure, preg_split('/(?<!\\\\)(?:\\\\\\\\)*\\./', $path));
	}
	$count = count($path);
	if($count) {
		$part = strtr(array_shift($path), ['\\.' => '.']);
		if(is_object($structure)) {
			if(count($path)) {
				if(property_exists($structure, $part)) {
					$structure->{$part} = rem($structure->{$part}, $path);
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
					$structure[$part] = rem($structure[$part], $path);
				}
			} else {
				unset($structure[$part]);
			}
		}
	}
	return $structure;
}
