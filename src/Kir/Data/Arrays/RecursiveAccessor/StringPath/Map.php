<?php
namespace Kir\Data\Arrays\RecursiveAccessor\StringPath;

use Exception;
use InvalidArgumentException;
use Kir\Data\Arrays\Helpers\StringPathToArrayPathConverter;
use Kir\Data\Arrays\RecursiveAccessor\ArrayPath;

class Map {
	/** @var ArrayPath\Map */
	private $delegate=null;
	/** @var string */
	private $separator = '';
	/** @var string */
	private $escapeBy = '';
	/** @var callable[] */
	private $listeners = [];
	
	/**
	 * @param array $data
	 * @param string $separator
	 * @param string $escapeBy
	 * @throws Exception
	 */
	public function __construct(array $data = array(), $separator = '.', $escapeBy = '\\') {
		if(strlen($separator) < 1) {
			throw new Exception('Invalid $separator');
		}
		if(strlen($separator) < 1) {
			throw new Exception('Invalid $escapeBy');
		}
		$this->delegate = new ArrayPath\Map($data);
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
	 * @return $this
	 */
	public function getNode($path) {
		$array = $this->getArray($path);
		return new static($array);
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
		$array = $this->asArray();
		foreach($this->listeners as $listener) {
			call_user_func($listener, $array, $path);
		}
		return $this;
	}
	
	/**
	 * @return array
	 */
	public function asArray() {
		return $this->delegate->asArray();
	}
	
	/**
	 * @param callable $fn
	 * @return $this
	 */
	public function onChange($fn) {
		$this->listeners[] = $fn;
		return $this;
	}
	
	/**
	 * @param string[]|string $path
	 * @throws InvalidArgumentException
	 * @return string[]
	 */
	private function extractPath($path) {
		if (is_array($path)) {
			return $path;
		} elseif (is_string($path)) {
			return StringPathToArrayPathConverter::convert($path, $this->separator, $this->escapeBy);
		}
		throw new InvalidArgumentException('Expected $path to by either array or string!');
	}
}
