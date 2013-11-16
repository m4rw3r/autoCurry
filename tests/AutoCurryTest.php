<?php

namespace m4rw3r;

use stdClass;

class AutoCurryTest extends \PHPUnit_Framework_TestCase
{
	public static function staticFunctionForCurry($a, $b, $c)
	{
		return [$a, $b, $c];
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	function testAutoCurryWithoutArgs()
	{
		autoCurry();
	}

	function testEmptyClosure()
	{
		$dummy = new stdClass();

		$f = autoCurry(function() use($dummy) { return $dummy; });

		$this->assertTrue(is_callable($f));
		$this->assertSame($f(), $dummy);
	}

	function testSingleParameter()
	{
		$dummy = new stdClass();

		$f = autoCurry(function($a) { return $a; });

		$this->assertTrue(is_callable($f));
		$this->assertSame($f($dummy), $dummy);
	}

	function testTwoParameters()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();

		$f = autoCurry(function($a, $b) { return [$a, $b]; });

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		$this->assertSame($f2($dummy2), [$dummy1, $dummy2]);
	}

	public function testThreeParameters()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();

		$f = autoCurry(function($a, $b, $c) { return [$a, $b, $c]; });

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		
		$f3 = $f2($dummy2);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f3($dummy3), [$dummy1, $dummy2, $dummy3]);
	}

	public function testMultipleParamsAtOneTime1()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();

		$f = autoCurry(function($a, $b) { return [$a, $b]; });

		$this->assertTrue(is_callable($f));
		$this->assertSame($f($dummy1, $dummy2), [$dummy1, $dummy2]);
	}

	public function testMultipleParamsAtOneTime2()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();

		$f = autoCurry(function($a, $b, $c) { return [$a, $b, $c]; });

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1, $dummy2);

		$this->assertTrue(is_callable($f2));
		$this->assertSame($f2($dummy3), [$dummy1, $dummy2, $dummy3]);
	}

	public function testMultipleParamsAtOneTime3()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();
		$dummy4 = new stdClass();
		$dummy5 = new stdClass();

		$f = autoCurry(function($a, $b, $c, $d, $e) { return [$a, $b, $c, $d, $e]; });

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1, $dummy2);

		$this->assertTrue(is_callable($f2));

		$f3 = $f2($dummy3, $dummy4);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f3($dummy5), [$dummy1, $dummy2, $dummy3, $dummy4, $dummy5]);
	}

	public function testSpecifyNumParams1()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();

		$f = autoCurry(function($a, $b, $c = 'foobar') { return [$a, $b, $c]; }, 2);

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		$this->assertSame($f2($dummy2), [$dummy1, $dummy2, 'foobar']);
	}

	public function testSpecifyNumParams2()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();

		$f = autoCurry(function($a) { return func_get_args(); }, 2);

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		$this->assertSame($f2($dummy2), [$dummy1, $dummy2]);
	}

	public function testEmptyParams()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();

		$f = autoCurry(function($a, $b) { return func_get_args(); });

		$this->assertTrue(is_callable($f));

		$f2 = $f();

		$this->assertTrue(is_callable($f2));

		$f3 = $f2($dummy1);

		$this->assertTrue(is_callable($f3));

		$f4 = $f3();

		$this->assertTrue(is_callable($f4));
		$this->assertSame($f4($dummy2), [$dummy1, $dummy2]);
	}

	public function testReuse1()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();

		$f = autoCurry(function($a) { return $a; });

		$this->assertTrue(is_callable($f));
		$this->assertSame($f($dummy1), $dummy1);
		$this->assertSame($f($dummy2), $dummy2);
	}

	public function testReuse2()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();
		$dummy4 = new stdClass();

		$f = autoCurry(function($a, $b) { return [$a, $b]; });

		$this->assertTrue(is_callable($f));

		$f1 = $f($dummy1);

		$this->assertTrue(is_callable($f1));

		$f2 = $f($dummy2);

		$this->assertTrue(is_callable($f2));

		$this->assertSame($f1($dummy3), [$dummy1, $dummy3]);
		$this->assertSame($f1($dummy4), [$dummy1, $dummy4]);
		$this->assertSame($f2($dummy3), [$dummy2, $dummy3]);
		$this->assertSame($f2($dummy4), [$dummy2, $dummy4]);
	}

	public function testStaticFunction1()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();

		$f = autoCurry('m4rw3r\AutoCurryTest::staticFunctionForCurry');

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		
		$f3 = $f2($dummy2);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f3($dummy3), [$dummy1, $dummy2, $dummy3]);
	}

	public function testStaticFunction2()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();

		$f = autoCurry(['m4rw3r\AutoCurryTest', 'staticFunctionForCurry']);

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		
		$f3 = $f2($dummy2);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f3($dummy3), [$dummy1, $dummy2, $dummy3]);
	}

	public function testInvokeObject()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();

		/* Have to use a real class here, as PHPUnit's getMock()
		 * creates an __invoke() method without any parameters.
		 */
		$o = new InvokeAbleObject1(function($params) use($dummy1, $dummy2, $dummy3) {
			$this->assertSame($params, [$dummy1, $dummy2]);

			return $dummy3;
		});

		$f = autoCurry($o);

		$this->assertTrue(is_callable($f));

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		$this->assertSame($f2($dummy2), $dummy3);
	}
}

class InvokeAbleObject1
{
	public function __construct(callable $func)
	{
		$this->func = $func;
	}

	public function __invoke($a, $b)
	{
		$f = $this->func;

		return $f(func_get_args());
	}
}
