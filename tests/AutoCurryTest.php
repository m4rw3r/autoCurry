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
		
		$f3 = $f($dummy2);

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

		$f3 = $f($dummy3, $dummy4);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f2($dummy5), [$dummy1, $dummy2, $dummy3, $dummy4, $dummy5]);
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
		
		$f3 = $f($dummy2);

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
		
		$f3 = $f($dummy2);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f3($dummy3), [$dummy1, $dummy2, $dummy3]);
	}
}
