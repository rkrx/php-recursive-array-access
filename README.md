Recursive array access for PHP5.3+
==================================

Access recursive arrays through an easy interface

## Why, what?

Example:

```php
$array = [];
$data = new ArrayPath\Accessor($array);
$data->set(['a', 'b', 'c'], 'test'); // equals $data['a']['b']['c'] = 'test';
print_r($data->asArray());

echo $data->getString(['a', 'b', 'd'], 'fallback'); // -> "fallback"
print_r($data->getArray(['a', 'b', 'd'], ['fallback'])); // -> ["fallback"]
```

You can also use strings as a path:

```php
$array = [];
$data = new StringPath\Accessor($array);
$data->set('a.b.c', 'test'); // equals $data['a']['b']['c'] = 'test';
print_r($data->asArray());

echo $data->getString('a.b.d', 'fallback'); // -> "fallback"
print_r($data->getArray('a.b.d', ['fallback'])); // -> ["fallback"]
```

## Composer:

```
"require": [
	"rkr/recursive-array-accessor": "2.*"
]
```
