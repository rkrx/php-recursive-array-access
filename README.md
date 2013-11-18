Recursive array access for PHP5.3+
==================================

Access recursive arrays through an easy interface

## Why, what?

Example:

```php
$array = [];
$data = new RecursiveAccessor($array);
$data->set(['a', 'b', 'c'], 'test'); // equals $data['a']['b']['c'] = 'test';
print_r($data->asArray());

echo $data->getString(['a', 'b', 'd'], 'fallback'); // -> "fallback"
print_r($data->getArray(['a', 'b', 'd'], ['fallback'])); // -> ["fallback"]
```

You can also use strings as a path:

```php
$array = [];
$data = new RecursiveAccessor($array);
$data->set('a.b.c', 'test'); // equals $data['a']['b']['c'] = 'test';
print_r($data->asArray());

echo $data->getString('a.b.d', 'fallback'); // -> "fallback"
print_r($data->getArray('a.b.d', ['fallback'])); // -> ["fallback"]
```

## Composer:

```
"require": [
	"rkr/recursive-array-accessor": "1.*"
]
```

## Interface

```php
class RecursiveAccessor {
	/**
	 * @param string[]|string $path
	 * @return bool
	 */
	public function has($path);

	/**
	 * @param string[]|string $path
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($path, $default = null);

	/**
	 * @param string[]|string $path
	 * @param int $default
	 * @return int
	 */
	public function getBool($path, $default = 0);

	/**
	 * @param string[]|string $path
	 * @param int $default
	 * @return int
	 */
	public function getInt($path, $default = 0);

	/**
	 * @param string[]|string $path
	 * @param float $default
	 * @return float
	 */
	public function getFloat($path, $default = 0.0);

	/**
	 * @param string[]|string $path
	 * @param string $default
	 * @return string
	 */
	public function getString($path, $default = '');

	/**
	 * @param string[]|string $path
	 * @param array $default
	 * @return array
	 */
	public function getArray($path, $default = array());

	/**
	 * @param string[]|string $path
	 * @return static[]
	 */
	public function getChildren($path);

	/**
	 * @param string[]|string $path
	 * @param mixed $value
	 * @return $this
	 */
	public function set($path, $value);

	/**
	 * @return array
	 */
	public function asArray();
}
```
