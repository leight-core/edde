<?php
declare(strict_types=1);

namespace Edde\Utils;

use Nette\Utils\Arrays;

class ArrayUtils {
	static public function mergeRecursive($arr1, $arr2): array {
		return Arrays::mergeTree($arr1, $arr2);
	}
}
