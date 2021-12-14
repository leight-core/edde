<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Property;

use Edde\Reflection\Dto\Type\Utils\IMixedType;
use Edde\Reflection\Dto\Type\Utils\MixedTypeTrait;

class MixedProperty extends AbstractProperty implements IMixedType {
	use MixedTypeTrait;
}
