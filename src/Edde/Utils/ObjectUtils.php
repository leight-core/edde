<?php
declare(strict_types=1);

namespace Edde\Utils;

use function is_object;

class ObjectUtils {
	/**
	 * @param object     $object
	 * @param array|null $value
	 *
	 * @return mixed
	 */
	static public function valueOf(object $object, ?array $value = null) {
		$item = $object;
		foreach ($value ?? [] as $v) {
			if (!is_object($item)) {
				break;
			}
			$item = $item->{$v};
		}
		return $item;
	}
}
