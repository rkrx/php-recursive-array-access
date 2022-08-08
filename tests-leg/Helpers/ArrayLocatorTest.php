<?php
namespace Kir\Data\Arrays\Helpers;

use PHPUnit\Framework\TestCase;

class ArrayLocatorTest extends TestCase {
	public function testHas(): void {
		$data = ['a' => 'b', 'c' => ['d' => ['e' => 'f']]];
		$this->assertTrue(ArrayLocator::has($data, ['c', 'd', 'e']));
		$this->assertFalse(ArrayLocator::has($data, ['a', 'd', 'e']));
		$this->assertTrue(ArrayLocator::has($data, ['a']));
	}

	public function testGet(): void {
		$data = ['a' => 'b', 'c' => ['d' => ['e' => 'f']]];
		$this->assertEquals('f', ArrayLocator::get($data, ['c', 'd', 'e']));
		$this->assertEquals(null, ArrayLocator::get($data, ['a', 'd', 'e']));
		$this->assertEquals('b', ArrayLocator::get($data, ['a']));
	}
}
 