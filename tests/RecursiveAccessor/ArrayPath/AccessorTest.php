<?php
namespace Kir\Data\Arrays\RecursiveAccessor\ArrayPath;

use PHPUnit\Framework\TestCase;

class AccessorTest extends TestCase {
	private $testData = array(
		'a' => array(
			'b' => array(
				'c' => 'd',
				'x' => true,
				'y' => 0,
				'z' => 0.0,
			)
		)
	);

	private $testData2 = array(
		'a' => array(
			'b' => array(
				'c' => 'd',
			)
		)
	);

	public function testHas() {
		$data = new Map($this->testData);
		$this->assertEquals(true, $data->has(array('a', 'b', 'c')));
		$this->assertEquals(false, $data->has(array('a', 'b', 'd')));
	}

	public function testGet() {
		$data = new Map($this->testData);
		$this->assertEquals('d', $data->get(array('a', 'b', 'c'), null));
		$this->assertEquals(null, $data->get(array('a', 'b', 'd'), null));
	}

	public function testGetBool() {
		$data = new Map($this->testData);
		$this->assertEquals(true, $data->getBool(array('a', 'b', 'x'), false));
		$this->assertEquals(false, $data->getBool(array('a', 'b', 'y'), true));
		$this->assertEquals(true, $data->getBool(array('a', 'b', 'n'), true));
	}

	public function testGetInt() {
		$data = new Map($this->testData);
		$this->assertEquals(0, $data->getInt(array('a', 'b', 'y'), 1));
		$this->assertEquals(0, $data->getInt(array('a', 'b', 'z'), 1));
		$this->assertEquals(1, $data->getInt(array('a', 'b', 'n'), 1));
	}

	public function testGetFloat() {
		$data = new Map($this->testData);
		$this->assertEquals(0.0, $data->getFloat(array('a', 'b', 'z'), 1.0));
		$this->assertEquals(0.0, $data->getFloat(array('a', 'b', 'y'), 1.0));
		$this->assertEquals(1.5, $data->getFloat(array('a', 'b', 'n'), 1.5));
	}

	public function testGetString() {
		$data = new Map($this->testData);
		$this->assertEquals('d', $data->getString(array('a', 'b', 'c'), 'e'));
		$this->assertEquals('0', $data->getString(array('a', 'b', 'y'), 'a'));
		$this->assertEquals('test', $data->getString(array('a', 'b', 'n'), 'test'));
		$this->assertEquals('', $data->getString(array('a', 'b'), 'aa'));
	}

	public function testGetArray() {
		$data = new Map($this->testData2);
		$this->assertEquals(array(), $data->getArray(array('a', 'b', 'c'), array('n')));
		$this->assertEquals(array('c' => 'd'), $data->getArray(array('a', 'b'), array('n')));
		$this->assertEquals(array('n'), $data->getArray(array('a', 'b', 'd'), array('n')));
	}

	public function testGetNode() {
		$data = new Map($this->testData);

		$value = $data->getNode(array('a', 'b'))->get(array('c'));
		$this->assertEquals('d', $value);

		$value = $data->getNode(array('a', 'b'))->get(array('d'));
		$this->assertEquals(null, $value);
	}

	public function testGetChildren() {
		$data = new Map($this->testData2);
		$children = $data->getChildren(array('a'));
		$this->assertCount(1, $children);
		$this->assertArrayHasKey('b', $children);
		$value = $children['b']->getString(array('c'));
		$this->assertEquals('d', $value);
	}

	public function testSet() {
		$data = new Map($this->testData);
		$this->assertEquals('d', $data->get(array('a', 'b', 'c'), null));
		$data->set(array('a', 'b', 'c'), 'e');
		$this->assertEquals('e', $data->get(array('a', 'b', 'c'), null));
	}
}
 