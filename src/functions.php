<?php
namespace raa;

use RuntimeException;
use stdClass;

/**
 * @template T of array|object
 * @param T $structure
 * @param string[]|string $path
 * @return bool
 */
function has($structure, $path) {
	return RArray::has($structure, $path);
}

/**
 * @template T of object|array
 * @param T $structure
 * @param string[]|string $path
 * @param mixed $default
 * @return mixed
 */
function get($structure, $path, $default = null) {
	return RArray::get($structure, $path, $default);
}

/**
 * @template T of object|array
 * @param T $structure
 * @param string[]|string $path
 * @param mixed $value
 * @return T
 */
function set($structure, $path, $value) {
	return RArray::set($structure, $path, $value);
}

/**
 * @template T of object|array
 * @param T $structure
 * @param string[]|string $path
 * @return T
 */
function rem($structure, $path) {
	return RArray::rem($structure, $path);
}
