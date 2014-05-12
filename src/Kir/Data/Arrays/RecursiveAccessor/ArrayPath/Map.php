<?php
namespace Kir\Data\Arrays\RecursiveAccessor\ArrayPath;

use Kir\Data\Arrays\RecursiveAccessor;

class Map {
	/**
	 * @var array
	 */
	private $data = array();

	/**
	 * @param array $data
	 */
	public function __construct(array $data = array()) {
		$this->data = $data;
	}

	/**
	 * @param string[] $path
	 * @return bool
	 */
	public function has(array $path) {
		return $this->hasPath($this->data, $path);
	}

	/**
	 * @param string[] $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function get(array $path, $default = null) {
		if ($this->hasPath($this->data, $path)) {
			return $this->getFromPath($this->data, $path);
		}
		return $default;
	}

	/**
	 * @param string[] $path
	 * @param int $default
	 * @return int
	 */
	public function getBool(array $path, $default = 0) {
		if ($this->hasPath($this->data, $path)) {
			$result = $this->getFromPath($this->data, $path);
			if(is_array($result)) {
				return false;
			}
			return (bool) (string) $result;
		}
		return $default;
	}

	/**
	 * @param string[] $path
	 * @param int $default
	 * @return int
	 */
	public function getInt(array $path, $default = 0) {
		if ($this->hasPath($this->data, $path)) {
			$result = $this->getFromPath($this->data, $path);
			if(is_array($result)) {
				return 0;
			}
			return (int) (string) $result;
		}
		return $default;
	}

	/**
	 * @param string[] $path
	 * @param float $default
	 * @return float
	 */
	public function getFloat(array $path, $default = 0.0) {
		if ($this->hasPath($this->data, $path)) {
			$result = $this->getFromPath($this->data, $path);
			if(is_array($result)) {
				return 0.0;
			}
			return (float) (string) $result;
		}
		return $default;
	}

	/**
	 * @param string[] $path
	 * @param string $default
	 * @return string
	 */
	public function getString(array $path, $default = '') {
		if ($this->hasPath($this->data, $path)) {
			$result = $this->getFromPath($this->data, $path);
			if(is_array($result)) {
				return '';
			}
			return (string) $result;
		}
		return $default;
	}

	/**
	 * @param string[] $path
	 * @param array $default
	 * @return array
	 */
	public function getArray(array $path, $default = array()) {
		if ($this->hasPath($this->data, $path)) {
			$result = $this->getFromPath($this->data, $path);
			if(!is_array($result)) {
				return array();
			}
			return $result;
		}
		return $default;
	}

	/**
	 * @param array $path
	 * @return $this
	 */
	public function getNode(array $path) {
		$array = $this->getArray($path);
		return new static($array);
	}

	/**
	 * @param string[] $path
	 * @return static[]
	 */
	public function getChildren(array $path) {
		$result = array();
		$array = $this->getArray($path);
		foreach($array as $key => $value) {
			$result[$key] = new static($value);
		}
		return $result;
	}

	/**
	 * @param string[] $path
	 * @param mixed $value
	 * @return $this
	 */
	public function set(array $path, $value) {
		$this->data = $this->setAsPath($this->data, $path, $value);
		return $this;
	}

	/**
	 * @return array
	 */
	public function asArray() {
		return $this->data;
	}

	/**
	 * @param string[] $data
	 * @param array $path
	 * @return bool
	 */
	private function hasPath(array $data, array $path) {
		if (!count($path)) {
			return false;
		}
		$value = $data;
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
	 * @param string[] $data
	 * @param array $path
	 * @return mixed
	 */
	private function getFromPath(array $data, array $path) {
		$key = array_shift($path);
		$value = null;
		if (array_key_exists($key, $data)) {
			$value = $data[$key];
			if (count($path)) {
				if (is_array($value)) {
					$value = $this->getFromPath($value, $path);
				} else {
					return null;
				}
			}
		}
		return $value;
	}

	/**
	 * @param string[] $data
	 * @param array $path
	 * @param mixed $value
	 * @return mixed
	 */
	private function setAsPath(array $data, array $path, $value) {
		$key = array_shift($path);
		if (!array_key_exists($key, $data)) {
			$data[$key] = array();
		}
		if (count($path)) {
			$data[$key] = $this->setAsPath($data[$key], $path, $value);
		} else {
			$data[$key] = $value;
		}
		return $data;
	}
} 