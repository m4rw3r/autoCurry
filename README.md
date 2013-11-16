autoCurry
=========

Enables automatic currying of functions. Every call to an ``autoCurry``ed function
will return a new function accepting the remaining function parameters until all
parameters have been gathered whereupon the wrapped function will be executed
and return its value.

Note that the function not only works on other functions, it also works on
objects implementing ``__invoke()``, closures, static methods and standard
object methods.


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

$names = array_map($namePrefix, ['Foo', 'Bar']);
/* array(2) { [0] => string(7) "TestFoo" [1] => string(7) "TestFoo" } */
```

Prototype
---------

```php
m4rw3r\autoCurry(callable [, num_params = false])
```

* ``callable``: The function/closure/method/object to curry
* ``num_params``: Sets the limit for the auto-curry, useful in cases where
  optional parameters are present or when a method accepting any number of
  parameters is being curryed.

``autoCurry()`` will automatically determine the number of parameters if
``num_params`` is ``false``, through the use of ``ReflectionMethod and
``ReflectionFunction``.
