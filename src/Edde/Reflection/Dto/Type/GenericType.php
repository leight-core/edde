<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type;

use Edde\Reflection\Dto\Type\Utils\GenericTypeTrait;
use Edde\Reflection\Dto\Type\Utils\IGenericType;

class GenericType extends AbstractType implements IGenericType {
	use GenericTypeTrait;
}
