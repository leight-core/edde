<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

use Edde\Reflection\Dto\Type\AbstractType;

interface IGenericType {
	public function type(): AbstractType;

	/**
	 * @return AbstractType[]
	 */
	public function generics(): array;
}
