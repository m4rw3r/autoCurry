<?php

namespace m4rw3r;

use ReflectionMethod;
use ReflectionFunction;

function autoCurry(callable $callable, $num_params = false)
{
	if($num_params === false) {
		$num_params = callableReflection($callable)->getNumberOfParameters();
	}

	return curryStep($callable, $num_params, []);
}

function curryStep($callable, $num_params, array $params)
{
	return function() use($callable, $num_params, $params) {
		$p = array_merge($params, func_get_args());

		if(count($p) >= $num_params) {
			return call_user_func_array($callable, $p);
		}

		return curryStep($callable, $num_params, $p);
	};
}

function callableReflection(callable $callable)
{
	if(is_array($callable) && count($callable) === 2) {
		return new ReflectionMethod(array_shift($callable), array_shift($callable));
	}
	elseif(is_string($callable) && strpos($callable, ':') !== false) {
		return new ReflectionMethod($callable);
	}
	elseif(is_object($callable)) {
		return new ReflectionMethod($callable, '__invoke');
	}
	else {
		return new ReflectionFunction($callable);
	}
}
