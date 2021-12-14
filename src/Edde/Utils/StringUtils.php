<?php
declare(strict_types=1);

namespace Edde\Utils;

use Generator;
use function is_array;

class StringUtils {
	const SEPARATOR_LIST = [
		'|',
		':',
		'.',
		'-',
		'_',
		'/',
		' ',
	];

	static public function lower(string $string): string {
		return mb_strtolower($string, 'UTF-8');
	}

	static public function substring(string $string, int $start, int $length = null): string {
		return mb_substr($string, $start, $length, 'UTF-8');
	}

	static public function firstLower(string $string): string {
		return self::lower(self::substring($string, 0, 1)) . self::substring($string, 1);
	}

	static public function capitalize(string $string): string {
		return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
	}

	static public function toCamelHump($input): string {
		return self::firstLower(self::toCamelCase($input));
	}

	static public function toCamelCase($input): string {
		return (string)str_replace('~', '', mb_convert_case(str_replace(self::SEPARATOR_LIST, '~', mb_strtolower(implode('~', is_array($input) ? $input : preg_split('~(?=[A-Z])~', $input, -1, PREG_SPLIT_NO_EMPTY)))), MB_CASE_TITLE, 'UTF-8'));
	}

	static public function fromCamelCase(string $string, int $index = null): array {
		$camel = preg_split('~(?=[A-Z])~', $string, -1, PREG_SPLIT_NO_EMPTY);
		return $index !== null ? array_slice($camel, $index) : $camel;
	}

	static public function recamel(string $string, string $glue = '-', int $index = 0): string {
		return mb_strtolower(implode($glue, self::fromCamelCase($string, $index)));
	}

	static public function match(string $string, string $pattern, bool $named = false, $trim = false): ?array {
		$match = null;
		if (($match = preg_match($pattern, $string, $match) ? $match : null) === null) {
			return is_array($trim) ? $trim : null;
		}
		if ($named && is_array($match)) {
			foreach ($match as $k => $v) {
				if (is_int($k) || ((is_array($trim) || $trim) && empty($v))) {
					unset($match[$k]);
				}
			}
		}
		if (is_array($trim)) {
			$match = array_merge($trim, $match);
		}
		return $match;
	}

	static public function matchAll(string $string, string $pattern, bool $named = false, $trim = false): array {
		$match = null;
		if (($match = preg_match_all($pattern, $string, $match) ? $match : null) === null) {
			return is_array($trim) ? $trim : [];
		}
		if ($named) {
			/** @noinspection ForeachSourceInspection */
			foreach ($match as $k => $v) {
				if (is_int($k) || ((is_array($trim) || $trim) && empty($v))) {
					unset($match[$k]);
				}
			}
		}
		if (is_array($trim)) {
			$match = array_merge($trim, $match);
		}
		return $match;
	}

	static public function extract(string $source, string $separator = '\\', int $index = -1): string {
		$sourceList = explode($separator, $source);
		return isset($sourceList[$index = ($index < 0 ? count($sourceList) + $index : $index)]) ? (string)$sourceList[$index] : '';
	}

	static public function normalize(string $string): string {
		$string = self::normalizeNewLines($string);
		$string = preg_replace('~[\x00-\x08\x0B-\x1F\x7F-\x9F]+~u', '', $string);
		$string = preg_replace('~[\t ]+$~m', '', $string);
		return trim($string, "\n");
	}

	static public function normalizeNewLines(string $string): string {
		return (string)str_replace([
			"\r\n",
			"\r",
		], "\n", $string);
	}

	static public function createIterator(string $string): Generator {
		$length = mb_strlen($string = self::normalizeNewLines($string));
		while ($length) {
			yield mb_substr($string, 0, 1);
			$length = mb_strlen($string = mb_substr($string, 1, $length));
		}
	}

	static public function className(string $string): string {
		return (string)str_replace(
			'°',
			'\\',
			self::toCamelCase(
				str_replace(
					[
						'.',
						'-',
					],
					[
						'°',
						'~',
					],
					$string
				)
			)
		);
	}

	static public function replaceFromStart(string $string, string $prefix) {
		if (substr($string, 0, strlen($prefix)) == $prefix) {
			$string = substr($string, strlen($prefix));
		}
		return $string;
	}
}
