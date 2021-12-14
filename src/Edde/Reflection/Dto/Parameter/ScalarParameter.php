<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Parameter;

use Edde\Reflection\Dto\Type\Utils\IScalarType;
use Edde\Reflection\Dto\Type\Utils\ScalarTypeTrait;

class ScalarParameter extends AbstractParameter implements IScalarType {
	use ScalarTypeTrait;
}
