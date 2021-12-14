<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Property;

use Edde\Reflection\Dto\Type\Utils\IUnknownType;
use Edde\Reflection\Dto\Type\Utils\UnknownTypeTrait;

class UnknownProperty extends AbstractProperty implements IUnknownType {
	use UnknownTypeTrait;
}
