<?php
namespace Kir\Data\Arrays\RecursiveAccessor\StringPath;

use Exception;
use InvalidArgumentException;
use Kir\Data\Arrays\Helpers\StringPathToArrayPathConverter;
use Kir\Data\Arrays\RecursiveAccessor\ArrayPath;
use RuntimeException;

/**
 * @template T of array<mixed, mixed>
 */
class Map {
	/** @var ArrayPath\Map */
	private $delegate=null;
	/** @var string */
	private $separator = '';
	/** @var string */
	private $escapeBy = '';
	
	/**
	 * @param T $data
	 * @param null|string $separator
	 * @param null|string $escapeBy
	 * @throws Exception
	 */
	public function __construct(array $data = [], $separator = '.', $escapeBy = '\\') {
		if(strlen((string) $separator) < 1) {
			throw new RuntimeException('Invalid $separator');
		}
		if(strlen((string) $escapeBy) < 1) {
			throw new RuntimeException('Invalid $escapeBy');
		}
		$this->delegate = new ArrayPath\Map($data);
		$this->separator = (string) $separator;
		$this->escapeBy = (string) $escapeBy;
	}
	
	/**
	 * @param string[]|string $path
	 * @return bool
	 */
	public function has($path) {
		$path = $this->extractPath($path);
		return $this->delegate->has($path);
	}
	
	/**
	 * @param string[]|string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($path, $default = null) {
		$path = $this->extractPath($path);
		return $this->delegate->get($path, $default);
	}
	
	/**
	 * @template D
	 * @param string[]|string $path
	 * @param D $default
	 * @return bool|D
	 */
	public function getBool($path, $default = false) {
		$path = $this->extractPath($path);
		return $this->delegate->getBool($path, $default);
	}
	
	/**
	 * @param string[]|string $path
	 * @param int $default
	 * @return int
	 */
	public function getInt($path, $default = 0) {
		$path = $this->extractPath($path);
		return $this->delegate->getInt($path, $default);
	}
	
	/**
	 * @param string[]|string $path
	 * @param float $default
	 * @return float
	 */
	public function getFloat($path, $default = 0.0) {
		$path = $this->extractPath($path);
		return $this->delegate->getFloat($path, $default);
	}
	
	/**
	 * @param string[]|string $path
	 * @param string $default
	 * @return string
	 */
	public function getString($path, $default = '') {
		$path = $this->extractPath($path);
		return $this->delegate->getString($path, $default);
	}
	
	/**
	 * @template D
	 * @param string[]|string $path
	 * @param D $default
	 * @return array<mixed, mixed>|D
	 */
	public function getArray($path, $default = []) {
		$path = $this->extractPath($path);
		return $this->delegate->getArray($path, $default);
	}
	
	/**
	 * @param string[]|string $path
	 * @return $this
	 */
	public function getNode($path) {
		$array = $this->getArray($path);
		return new static($array);
	}
	
	/**
	 * @param string[]|string $path
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
	 * @param string[]|string $path
	 * @return $this
	 */
	public function remove($path) {
		$path = $this->extractPath($path);
		$this->delegate->remove($path);
		return $this;
	}
	
	/**
	 * @return array<mixed, mixed>
	 */
	public function asArray() {
		return $this->delegate->asArray();
	}
	
	/**
	 * @param callable $fn
	 * @return $this
	 */
	public function onChange($fn) {
		$this->delegate->onChange($fn);
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
		}
		
		if (is_string($path)) {
			return StringPathToArrayPathConverter::convert($path, $this->separator, $this->escapeBy);
		}
		
		throw new InvalidArgumentException('Expected $path to by either array or string!');
	}
}
