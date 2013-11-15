autoCurry
=========

Enables automatic currying of functions. Every call to an ``autoCurry``ed function
will return a new function accepting the remaining function parameters until all
parameters have been gathered whereupon the wrapped function will be executed
and return its value.

Usage
-----

```php
$add = m4rw3r\autoCurry(function($a, $b) {
	return $a + $b;
});

$add5 = $add(5);

var_dump($add5(3)); /* int(8) */

$concat = m4rw3r\autoCurry(function($a, $b) {
	return $a . $b;
});

$namePrefix = $concat('Test');

$names = array_map($namePrefix, array('Foo', 'Bar'));
/* array(2) { [0] => string(7) "TestFoo" [1] => string(7) "TestFoo" } */
```
