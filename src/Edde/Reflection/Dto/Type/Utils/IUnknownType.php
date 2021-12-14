<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

interface IUnknownType {
	/**
	 * Return an unresolved "unknown" type.
	 *
	 * @return string
	 */
	public function type(): string;
}
