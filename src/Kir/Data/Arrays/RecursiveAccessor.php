<?php
namespace Kir\Data\Arrays;

/**
 * Class Data
 */
class RecursiveAccessor {
	/**
	 * @var array
	 */
	private $data = array();

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
		$this->data = $data;
		$this->separator = $separator;
		$this->escapeBy = $escapeBy;
	}

	/**
	 * @param string[]|string $path
	 * @return bool
	 */
	public function has($path) {
		$path = $this->extractPath($path);
		return $this->hasPath($this->data, $path);
	}

	/**
	 * @param string[]|string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($path, $default = null) {
		$path = $this->extractPath($path);
		if ($this->hasPath($this->data, $path)) {
			return $this->getFromPath($this->data, $path);
		}
		return $default;
	}

	/**
	 * @param string[]|string $path
	 * @param int $default
	 * @return int
	 */
	public function getBool($path, $default = 0) {
		$path = $this->extractPath($path);
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
	 * @param string[]|string $path
	 * @param int $default
	 * @return int
	 */
	public function getInt($path, $default = 0) {
		$path = $this->extractPath($path);
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
	 * @param string[]|string $path
	 * @param float $default
	 * @return float
	 */
	public function getFloat($path, $default = 0.0) {
		$path = $this->extractPath($path);
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
	 * @param string[]|string $path
	 * @param string $default
	 * @return string
	 */
	public function getString($path, $default = '') {
		$path = $this->extractPath($path);
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
	 * @param string[]|string $path
	 * @param array $default
	 * @return array
	 */
	public function getArray($path, $default = array()) {
		$path = $this->extractPath($path);
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
	 * @param string[]|string $path
	 * @return static[]
	 */
	public function getChildren($path) {
		$result = array();
		$array = $this->getArray($path);
		foreach($array as $key => $value) {
			$result[$key] = new static($value);
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