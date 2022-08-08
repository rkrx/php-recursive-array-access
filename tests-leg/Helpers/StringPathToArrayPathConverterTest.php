<?php
namespace Kir\Data\Arrays\Helpers;

use PHPUnit\Framework\TestCase;

class StringPathToArrayPathConverterTest extends TestCase {
	public function testConvert(): void {
		$path = StringPathToArrayPathConverter::convert('this.is.a.test', '.', '\\');
		$this->assertEquals(array('this', 'is', 'a', 'test'), $path);
	}

	public function testConvertWithDifferentSeparator(): void {
		$path = StringPathToArrayPathConverter::convert('this|is|a|test', '|', '\\');
		$this->assertEquals(array('this', 'is', 'a', 'test'), $path);
	}

	public function testConvertWithEscapeChar(): void {
		$path = StringPathToArrayPathConverter::convert('this.is.a\\.test', '.', '\\');
		$this->assertEquals(array('this', 'is', 'a.test'), $path);
	}

	public function testConvertWithEscapedEscapeChar1(): void {
		$path = StringPathToArrayPathConverter::convert('this.is.a\\\\.test', '.', '\\');
		$this->assertEquals(array('this', 'is', 'a\\', 'test'), $path);
	}

	public function testConvertWithEscapedEscapeChar2(): void {
		$path = StringPathToArrayPathConverter::convert('this.is.a\\\\\\\\.test', '.', '\\');
		$this->assertEquals(array('this', 'is', 'a\\\\', 'test'), $path);
	}

	public function testConvertWithEscapedEscapeChar3(): void {
		$path = StringPathToArrayPathConverter::convert('this.is.a\\\\\\\\\\.test', '.', '\\');
		$this->assertEquals(array('this', 'is', 'a\\\\.test'), $path);
	}
}
 