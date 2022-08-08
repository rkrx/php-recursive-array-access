<?php
namespace Kir\Data\Arrays\RecursiveAccessor\StringPath;

use PHPUnit\Framework\TestCase;

class AccessorTest extends TestCase {
	/** @var scalar[][][] */
	private $testData = [
		'a' => [
			'b' => [
				'c' => 'd',
				'x' => true,
				'y' => 0,
				'z' => 0.0,
			]
		]
	];

	/** @var scalar[][][] */
	private $testData2 = [
		'a' => [
			'b' => [
				'c' => 'd',
			]
		]
	];

	public function testHas(): void {
		$data = new Map($this->testData);
		$this->assertEquals(true, $data->has('a.b.c'));
		$this->assertEquals(false, $data->has('a.b.d'));
	}

	public function testGet(): void {
		$data = new Map($this->testData);
		$this->assertEquals('d', $data->get('a.b.c', null));
		$this->assertEquals(null, $data->get('a.b.d', null));
	}

	public function testGetBool(): void {
		$data = new Map($this->testData);
		$this->assertEquals(true, $data->getBool('a.b.x', false));
		$this->assertEquals(false, $data->getBool('a.b.y', true));
		$this->assertEquals(true, $data->getBool('a.b.n', true));
	}

	public function testGetInt(): void {
		$data = new Map($this->testData);
		$this->assertEquals(0, $data->getInt('a.b.y', 1));
		$this->assertEquals(0, $data->getInt('a.b.z', 1));
		$this->assertEquals(1, $data->getInt('a.b.n', 1));
	}

	public function testGetFloat(): void {
		$data = new Map($this->testData);
		$this->assertEquals(0.0, $data->getFloat('a.b.z', 1.0));
		$this->assertEquals(0.0, $data->getFloat('a.b.y', 1.0));
		$this->assertEquals(1.5, $data->getFloat('a.b.n', 1.5));
	}

	public function testGetString(): void {
		$data = new Map($this->testData);
		$this->assertEquals('d', $data->getString('a.b.c', 'e'));
		$this->assertEquals('0', $data->getString('a.b.y', 'a'));
		$this->assertEquals('test', $data->getString('a.b.n', 'test'));
		$this->assertEquals('', $data->getString('a.b', 'aa'));
	}

	public function testGetArray(): void {
		$data = new Map($this->testData2);
		$this->assertEquals([], $data->getArray('a.b.c', ['n']));
		$this->assertEquals(['c' => 'd'], $data->getArray('a.b', ['n']));
		$this->assertEquals(['n'], $data->getArray('a.b.d', ['n']));
	}

	public function testGetNode(): void {
		$data = new Map($this->testData);

		$value = $data->getNode('a.b')->get('c');
		$this->assertEquals('d', $value);

		$value = $data->getNode('a.b')->get('d');
		$this->assertEquals(null, $value);
	}

	public function testGetChildren(): void {
		$data = new Map($this->testData2);
		$children = $data->getChildren('a');
		$this->assertCount(1, $children);
		$this->assertArrayHasKey('b', $children);
		$value = $children['b']->getString('c');
		$this->assertEquals('d', $value);
	}

	public function testSet(): void {
		$data = new Map($this->testData);
		$this->assertEquals('d', $data->get('a.b.c', null));
		$data->set(['a', 'b', 'c'], 'e');
		$this->assertEquals('e', $data->get('a.b.c', null));
	}
}
 