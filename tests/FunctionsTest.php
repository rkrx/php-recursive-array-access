<?php
namespace raa;

use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase {
	public function testHasWithArrayStructureAndArrayPath() {
		$this->assertEquals(true, has(['a' => ['b' => ['c' => 123]]], ['a', 'b', 'c']));
		$this->assertEquals(false, has(['a' => ['b' => ['c' => 123]]], ['a', 'b', 'd']));
	}
	
	public function testHasWithObjectStructureAndArrayPath() {
		$this->assertEquals(true, has((object) ['a' => ['b' => (object) ['c' => 123]]], ['a', 'b', 'c']));
		$this->assertEquals(false, has((object) ['a' => ['b' => (object) ['c' => 123]]], ['a', 'b', 'd']));
	}
	
	public function testHasWithArrayStructureAndStringPath() {
		$this->assertEquals(true, has(['a' => ['b' => ['c' => 123]]], 'a.b.c'));
		$this->assertEquals(false, has(['a' => ['b' => ['c' => 123]]], 'a.b.d'));
	}
	
	public function testHasWithObjectStructureAndStringPath() {
		$this->assertEquals(true, has((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.c'));
		$this->assertEquals(false, has((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.d'));
	}
	
	public function testGetWithArrayStructureAndArrayPath() {
		$this->assertEquals(123, get(['a' => ['b' => ['c' => 123]]], ['a', 'b', 'c']));
		$this->assertEquals(null, get(['a' => ['b' => ['c' => 123]]], ['a', 'b', 'd']));
	}
	
	public function testGetWithObjectStructureAndArrayPath() {
		$this->assertEquals(123, get((object) ['a' => ['b' => (object) ['c' => 123]]], ['a', 'b', 'c']));
		$this->assertEquals(null, get((object) ['a' => ['b' => (object) ['c' => 123]]], ['a', 'b', 'd']));
	}
	
	public function testGetWithArrayStructureAndStringPath() {
		$this->assertEquals(123, get(['a' => ['b' => ['c' => 123]]], 'a.b.c'));
		$this->assertEquals(null, get(['a' => ['b' => ['c' => 123]]], 'a.b.d'));
	}
	
	public function testGetWithObjectStructureAndStringPath() {
		$this->assertEquals(123, get((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.c'));
		$this->assertEquals(null, get((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.d'));
	}
	
	public function testGetWithStringPathContainingDot() {
		$this->assertEquals(123, get((object) ['a' => ['b.c' => (object) ['d' => 123]]], 'a.b\\.c.d'));
		$this->assertEquals(null, get((object) ['a' => ['b.c' => (object) ['d' => 123]]], 'a.b\\\\.c.d'));
	}
	
	public function testSet() {
		$struct = set((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.c', 456);
		$this->assertEquals(456, get($struct, 'a.b.c'));
	}
	
	public function testRem() {
		$this->assertEquals((object) ['a' => ['b' => (object) ['d' => 456]]], rem((object) ['a' => ['b' => (object) ['c' => 123, 'd' => 456]]], 'a.b.c'));
	}
}