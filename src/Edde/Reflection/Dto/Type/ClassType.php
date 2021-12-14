<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type;

use Edde\Reflection\Dto\Type\Utils\ClassTypeTrait;
use Edde\Reflection\Dto\Type\Utils\IClassType;

/**
 * This type references to another class by it's name.
 */
class ClassType extends AbstractType implements IClassType {
	use ClassTypeTrait;
}
