<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Property;

use Edde\Reflection\Dto\Type\Utils\IScalarType;
use Edde\Reflection\Dto\Type\Utils\ScalarTypeTrait;

class ScalarProperty extends AbstractProperty implements IScalarType {
	use ScalarTypeTrait;
}
