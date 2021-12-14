<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Parameter;

use Edde\Reflection\Dto\Type\Utils\IMixedType;
use Edde\Reflection\Dto\Type\Utils\MixedTypeTrait;

class MixedParameter extends AbstractParameter implements IMixedType {
	use MixedTypeTrait;
}
