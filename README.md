Recursive array access for PHP5.3+
==================================

[![Build Status](https://travis-ci.org/rkrx/php-recursive-array-access.svg?branch=master)](https://travis-ci.org/rkrx/php-recursive-array-access)

Access n-dimensional arrays through an easy interface

## Why, what?

Example:

```php
// PHP5.4-style array syntax
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
"require": [{
    "rkr/recursive-array-accessor": "2.*"
}]
```
