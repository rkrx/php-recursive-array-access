<?php
namespace Kir\Data\Arrays\RecursiveAccessor\StringPath;

use Kir\Data\Arrays\RecursiveAccessor\ArrayPath;

class Accessor {
	/**
	 * @var ArrayPath\Accessor
	 */	
	private $delegate=null;

	/**
	 * @var string
	 */
	private $separator = '';

	/**
	 * @var string
	 */
	private $escapeBy = '';

	/**
	 * @param array $data
	 * @param string $separator
	 * @param string $escapeBy
	 */
	public function __construct(array $data = array(), $separator = '.', $escapeBy = '\\') {
		$this->delegate = new ArrayPath\Accessor($data, $separator, $escapeBy);
		$this->separator = $separator;
		$this->escapeBy = $escapeBy;
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	public function has($path) {
		$path = $this->extractPath($path);
		return $this->delegate->has($path);
	}

	/**
	 * @param string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($path, $default = null) {
		$path = $this->extractPath($path);
		return $this->delegate->get($path, $default);
	}

	/**
	 * @param string $path
	 * @param int $default
	 * @return int
	 */
	public function getBool($path, $default = 0) {
		$path = $this->extractPath($path);
		return $this->delegate->getBool($path, $default);
	}

	/**
	 * @param string $path
	 * @param int $default
	 * @return int
	 */
	public function getInt($path, $default = 0) {
		$path = $this->extractPath($path);
		return $this->delegate->getInt($path, $default);
	}

	/**
	 * @param string $path
	 * @param float $default
	 * @return float
	 */
	public function getFloat($path, $default = 0.0) {
		$path = $this->extractPath($path);
		return $this->delegate->getFloat($path, $default);
	}

	/**
	 * @param string $path
	 * @param string $default
	 * @return string
	 */
	public function getString($path, $default = '') {
		$path = $this->extractPath($path);
		return $this->delegate->getString($path, $default);
	}

	/**
	 * @param string $path
	 * @param array $default
	 * @return array
	 */
	public function getArray($path, $default = array()) {
		$path = $this->extractPath($path);
		return $this->delegate->getArray($path, $default);
	}

	/**
	 * @param string $path
	 * @return static[]
	 */
	public function getChildren($path) {
		$path = $this->extractPath($path);
		$result = array();
		$children = $this->delegate->getChildren($path);
		foreach($children as $key => $child) {
			$result[$key] = new static($child->asArray());
		}
		return $result;
	}

	/**
	 * @param string[]|string $path
	 * @param mixed $value
	 * @return $this
	 */
	public function set($path, $value) {
		$path = $this->extractPath($path);
		$this->delegate->set($path, $value);
		return $this;
	}

	/**
	 * @return array
	 */
	public function asArray() {
		return $this->delegate->asArray();
	}

	/**
	 * @param string[]|string $path
	 * @throws \InvalidArgumentException
	 * @return array
	 */
	private function extractPath($path) {
		if (is_array($path)) {
			return $path;
		} elseif (is_string($path)) {
			return $this->extractString($path);
		}
		throw new \InvalidArgumentException("Expected \$path to by either array or string!");
	}

	/**
	 * @param string $string
	 * @return array
	 */
	private function extractString($string) {
		$result = array();
		$last = $pos = 0;
		while (true) {
			$pos = strpos($string, $this->separator, max($last, $pos));
			if ($pos > 0 && $string[$pos - 1] === $this->escapeBy) {
				$pos++;
				continue;
			}
			if ($pos === false) {
				$result[] = substr($string, $last);
				return $result;
			}
			$result[] = substr($string, $last, $pos - $last);
			$last = $pos + 1;
		}
	}
} 