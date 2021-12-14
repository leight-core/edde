<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

interface IScalarType {
	public function type(): string;
}
