<?php
namespace Kir\Data\Arrays\Helpers;

use PHPUnit\Framework\TestCase;

class ArrayLocatorTest extends TestCase {
	public function testHas() {
		$data = array('a' => 'b', 'c' => array('d' => array('e' => 'f')));
		$this->assertTrue(ArrayLocator::has($data, array('c', 'd', 'e')));
		$this->assertFalse(ArrayLocator::has($data, array('a', 'd', 'e')));
		$this->assertTrue(ArrayLocator::has($data, array('a')));
	}

	public function testGet() {
		$data = array('a' => 'b', 'c' => array('d' => array('e' => 'f')));
		$this->assertEquals('f', ArrayLocator::get($data, array('c', 'd', 'e')));
		$this->assertEquals(null, ArrayLocator::get($data, array('a', 'd', 'e')));
		$this->assertEquals('b', ArrayLocator::get($data, array('a')));
	}
}
 