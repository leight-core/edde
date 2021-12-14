<?php
declare(strict_types=1);

namespace Edde\Utils;

use Graze\ArrayMerger\RecursiveArrayMerger;

class ArrayUtils {
	static public function mergeRecursive($arr1, $arr2): array {
		return (new RecursiveArrayMerger())->merge($arr1, $arr2);
	}
}
