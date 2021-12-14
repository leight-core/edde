<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Parameter;

use Edde\Reflection\Dto\Type\Utils\ClassTypeTrait;
use Edde\Reflection\Dto\Type\Utils\IClassType;

class ClassParameter extends AbstractParameter implements IClassType {
	use ClassTypeTrait;
}
