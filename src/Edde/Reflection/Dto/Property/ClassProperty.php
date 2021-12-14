<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Property;

use Edde\Reflection\Dto\Type\Utils\ClassTypeTrait;
use Edde\Reflection\Dto\Type\Utils\IClassType;

class ClassProperty extends AbstractProperty implements IClassType {
	use ClassTypeTrait;
}
