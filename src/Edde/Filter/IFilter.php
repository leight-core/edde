<?php
declare(strict_types=1);

namespace Edde\Filter;

interface IFilter {
	/**
	 * Just filter (convert) the given value to another value.
	 *
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function filter($value);
}
