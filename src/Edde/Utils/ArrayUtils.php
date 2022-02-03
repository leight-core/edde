<?php
declare(strict_types=1);

namespace Edde\Utils;

use Nette\Utils\Arrays;
use function array_sum;
use function count;
use function max;

class ArrayUtils {
	static public function mergeRecursive($arr1, $arr2): array {
		return Arrays::mergeTree($arr1, $arr2);
	}

	static public function avg(array $values): float {
		return array_sum($values) / (max(count($values), 1));
	}

	static public function median(array $array): float {
		$mid = floor((($count = count($array)) - 1) / 2);
		return $count % 2 ? $array[$mid] : (($array[$mid] + $array[$mid + 1]) / 2);
	}
}
