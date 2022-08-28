<?php
declare(strict_types=1);

namespace Edde\Utils;

use function is_object;

class ObjectUtils {
	/**
	 * Get a value from an object by the given property array (like foo->bar->goo, [foo, bar, goo].
	 *
	 * @param object     $object
	 * @param array|null $value
	 *
	 * @return mixed
	 */
	static public function valueOf(object $object, ?array $value = null) {
		/**
		 * Get an initial object to jump on
		 */
		$item = $object;
		foreach ($value ?? [] as $v) {
			/**
			 * If item is not an object (even if it's an array), do not continue; this is a strict
			 * check to jump only on an objects, so one who is using this method must convert "object like" arrays
			 * into an actual objects.
			 */
			if (!is_object($item)) {
				break;
			}
			/**
			 * Move down in the object tree and check, if there is another value being requested down in the tree.
			 */
			$item = $item->{$v} ?? null;
		}
		return $item;
	}
}
