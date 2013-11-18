<?php
namespace Kir\Data\Arrays\RecursiveAccessor\StringPath;

class AccessorTest extends \PHPUnit_Framework_TestCase {
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
		$data = new Accessor($this->testData);
		$this->assertEquals(true, $data->has('a.b.c'));
		$this->assertEquals(false, $data->has('a.b.d'));
	}

	public function testGet() {
		$data = new Accessor($this->testData);
		$this->assertEquals('d', $data->get('a.b.c', null));
		$this->assertEquals(null, $data->get('a.b.d', null));
	}

	public function testGetBool() {
		$data = new Accessor($this->testData);
		$this->assertEquals(true, $data->getBool('a.b.x', false));
		$this->assertEquals(false, $data->getBool('a.b.y', true));
		$this->assertEquals(true, $data->getBool('a.b.n', true));
	}

	public function testGetInt() {
		$data = new Accessor($this->testData);
		$this->assertEquals(0, $data->getInt('a.b.y', 1));
		$this->assertEquals(0, $data->getInt('a.b.z', 1));
		$this->assertEquals(1, $data->getInt('a.b.n', 1));
	}

	public function testGetFloat() {
		$data = new Accessor($this->testData);
		$this->assertEquals(0.0, $data->getFloat('a.b.z', 1.0));
		$this->assertEquals(0.0, $data->getFloat('a.b.y', 1.0));
		$this->assertEquals(1.5, $data->getFloat('a.b.n', 1.5));
	}

	public function testGetString() {
		$data = new Accessor($this->testData);
		$this->assertEquals('d', $data->getString('a.b.c', 'e'));
		$this->assertEquals('0', $data->getString('a.b.y', 'a'));
		$this->assertEquals('test', $data->getString('a.b.n', 'test'));
		$this->assertEquals('', $data->getString('a.b', 'aa'));
	}

	public function testGetArray() {
		$data = new Accessor($this->testData2);
		$this->assertEquals(array(), $data->getArray('a.b.c', array('n')));
		$this->assertEquals(array('c' => 'd'), $data->getArray('a.b', array('n')));
		$this->assertEquals(array('n'), $data->getArray('a.b.d', array('n')));
	}

	public function testGetChildren() {
		$data = new Accessor($this->testData2);
		$children = $data->getChildren('a');
		$this->assertCount(1, $children);
		$this->assertArrayHasKey('b', $children);
		$value = $children['b']->getString('c');
		$this->assertEquals('d', $value);
	}

	public function testSet() {
		$data = new Accessor($this->testData);
		$this->assertEquals('d', $data->get('a.b.c', null));
		$data->set(array('a', 'b', 'c'), 'e');
		$this->assertEquals('e', $data->get('a.b.c', null));
	}
}
 