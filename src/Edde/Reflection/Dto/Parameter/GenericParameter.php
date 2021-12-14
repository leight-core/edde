<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Parameter;

use Edde\Reflection\Dto\Type\Utils\GenericTypeTrait;
use Edde\Reflection\Dto\Type\Utils\IGenericType;

class GenericParameter extends AbstractParameter implements IGenericType {
	use GenericTypeTrait;
}
