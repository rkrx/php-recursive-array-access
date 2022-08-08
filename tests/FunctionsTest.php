<?php
namespace raa;

use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase {
	public function testHasWithArrayStructureAndArrayPath(): void {
		self::assertEquals(true, has(['a' => ['b' => ['c' => 123]]], ['a', 'b', 'c']));
		self::assertEquals(false, has(['a' => ['b' => ['c' => 123]]], ['a', 'b', 'd']));
	}
	
	public function testHasWithObjectStructureAndArrayPath(): void {
		self::assertEquals(true, has((object) ['a' => ['b' => (object) ['c' => 123]]], ['a', 'b', 'c']));
		self::assertEquals(false, has((object) ['a' => ['b' => (object) ['c' => 123]]], ['a', 'b', 'd']));
	}
	
	public function testHasWithArrayStructureAndStringPath(): void {
		self::assertEquals(true, has(['a' => ['b' => ['c' => 123]]], 'a.b.c'));
		self::assertEquals(false, has(['a' => ['b' => ['c' => 123]]], 'a.b.d'));
	}
	
	public function testHasWithObjectStructureAndStringPath(): void {
		self::assertEquals(true, has((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.c'));
		self::assertEquals(false, has((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.d'));
	}
	
	public function testGetWithArrayStructureAndArrayPath(): void {
		self::assertEquals(123, get(['a' => ['b' => ['c' => 123]]], ['a', 'b', 'c']));
		self::assertEquals(null, get(['a' => ['b' => ['c' => 123]]], ['a', 'b', 'd']));
	}
	
	public function testGetWithObjectStructureAndArrayPath(): void {
		self::assertEquals(123, get((object) ['a' => ['b' => (object) ['c' => 123]]], ['a', 'b', 'c']));
		self::assertEquals(null, get((object) ['a' => ['b' => (object) ['c' => 123]]], ['a', 'b', 'd']));
	}
	
	public function testGetWithArrayStructureAndStringPath(): void {
		self::assertEquals(123, get(['a' => ['b' => ['c' => 123]]], 'a.b.c'));
		self::assertEquals(null, get(['a' => ['b' => ['c' => 123]]], 'a.b.d'));
	}
	
	public function testGetWithObjectStructureAndStringPath(): void {
		self::assertEquals(123, get((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.c'));
		self::assertEquals(null, get((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.d'));
	}
	
	public function testGetWithStringPathContainingDot(): void {
		self::assertEquals(123, get((object) ['a' => ['b.c' => (object) ['d' => 123]]], 'a.b\\.c.d'));
		self::assertEquals(null, get((object) ['a' => ['b.c' => (object) ['d' => 123]]], 'a.b\\\\.c.d'));
	}
	
	public function testSet(): void {
		$struct = set((object) ['a' => ['b' => (object) ['c' => 123]]], 'a.b.c', 456);
		self::assertEquals(456, get($struct, 'a.b.c'));
	}
	
	public function testRem(): void {
		self::assertEquals((object) ['a' => ['b' => (object) ['d' => 456]]], rem((object) ['a' => ['b' => (object) ['c' => 123, 'd' => 456]]], 'a.b.c'));
	}
}