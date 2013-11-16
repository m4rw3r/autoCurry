<?php

namespace m4rw3r;

use ReflectionMethod;
use ReflectionFunction;

function autoCurry(callable $callable, $num_params = false)
{
	if($num_params === false) {
		$num_params = callableReflection($callable)->getNumberOfParameters();
	}

	$params = [];

	$curry = function() use($callable, $num_params, &$curry, &$params) {
		$params = array_merge($params, func_get_args());

		if(count($params) >= $num_params) {
			return call_user_func_array($callable, $params);
		}

		return $curry;
	};

	return $curry;
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
