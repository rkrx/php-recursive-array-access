<?php
namespace Kir\Data\Arrays\Helpers;

class StringPathToArrayPathConverter {
	/**
	 * @param $path
	 * @param string $separator
	 * @param string $escapeBy
	 * @return array
	 */
	public static function convert($path, $separator = '.', $escapeBy = '\\') {
		if(!strpos($path, $escapeBy)) {
			return explode($separator, $path);
		}
		$tokens = self::split($path, [$separator, $escapeBy]);
		$result = self::separate($tokens, $separator, $escapeBy);
		return $result;
	}

	/**
	 * @param string $string
	 * @param array $separators
	 * @return array
	 */
	public static function split($string, array $separators) {
		$result = array();
		$len = strlen($string);
		$start = 0;
		for($i = 0; $i < $len; $i++) {
			foreach($separators as $separator) {
				$seplen = strlen($separator);
				if(substr($string, $i, $seplen) == $separator) {
					if($i - $start > 0) {
						$result[] = substr($string, $start, $i - $start);
					}
					$result[] = $separator;
					$start = $i + $seplen;
				}
			}
		}
		if($start < strlen($string)) {
			$result[] = substr($string, $start);
		}
		return $result;
	}

	/**
	 * @param array $tokens
	 * @param string $separator
	 * @param string $escapeBy
	 * @return string[]
	 */
	private static function separate(array $tokens, $separator, $escapeBy) {
		$result = array('');
		$index = 0;
		for($pos = 0; $pos < count($tokens); $pos++) {
			if($tokens[$pos] === $separator) {
				if($pos > 0 && $tokens[$pos - 1] === $escapeBy) {
					$result[$index] .= $tokens[$pos];
				} else {
					$result[++$index] = '';
				}
			} elseif($tokens[$pos] === $escapeBy) {
				if($pos > 0 && $tokens[$pos - 1] === $escapeBy) {
					$result[$index] .= $tokens[$pos];
					$tokens[$pos] = null;
				}
			} else {
				$result[$index] .= $tokens[$pos];
			}
		}
		return $result;
	}
} 