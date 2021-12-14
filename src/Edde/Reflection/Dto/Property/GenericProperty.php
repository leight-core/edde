<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Property;

use Edde\Reflection\Dto\Type\Utils\GenericTypeTrait;
use Edde\Reflection\Dto\Type\Utils\IGenericType;

class GenericProperty extends AbstractProperty implements IGenericType {
	use GenericTypeTrait;
}
