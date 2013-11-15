<?php

namespace m4rw3r;

use stdClass;

class AutoCurryTest extends \PHPUnit_Framework_TestCase
{
	public static function staticFunctionForCurry($a, $b, $c)
	{
		return array($a, $b, $c);
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

		$this->assertSame($f(), $dummy);
	}

	function testSingleParameter()
	{
		$dummy = new stdClass();

		$f = autoCurry(function($a) { return $a; });

		$this->assertSame($f($dummy), $dummy);
	}

	function testTwoParameters()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();

		$f = autoCurry(function($a, $b) { return array($a, $b); });

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		$this->assertSame($f2($dummy2), array($dummy1, $dummy2));
	}

	public function testThreeParameters()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();

		$f = autoCurry(function($a, $b, $c) { return array($a, $b, $c); });

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		
		$f3 = $f($dummy2);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f3($dummy3), array($dummy1, $dummy2, $dummy3));
	}

	public function testStaticFunction1()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();

		$f = autoCurry('m4rw3r\AutoCurryTest::staticFunctionForCurry');

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		
		$f3 = $f($dummy2);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f3($dummy3), array($dummy1, $dummy2, $dummy3));
	}

	public function testStaticFunction2()
	{
		$dummy1 = new stdClass();
		$dummy2 = new stdClass();
		$dummy3 = new stdClass();

		$f = autoCurry(array('m4rw3r\AutoCurryTest', 'staticFunctionForCurry'));

		$f2 = $f($dummy1);

		$this->assertTrue(is_callable($f2));
		
		$f3 = $f($dummy2);

		$this->assertTrue(is_callable($f3));
		$this->assertSame($f3($dummy3), array($dummy1, $dummy2, $dummy3));
	}
}
