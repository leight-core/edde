<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type;

use Edde\Reflection\Dto\Type\Utils\IMixedType;
use Edde\Reflection\Dto\Type\Utils\MixedTypeTrait;

/**
 * Mixed type is... a mixed type - thus an unknown type.
 */
class MixedType extends AbstractType implements IMixedType {
	use MixedTypeTrait;
}
