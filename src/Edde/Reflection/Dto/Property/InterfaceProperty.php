<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Property;

use Edde\Reflection\Dto\Type\Utils\IInterfaceType;
use Edde\Reflection\Dto\Type\Utils\InterfaceTypeTrait;

class InterfaceProperty extends AbstractProperty implements IInterfaceType {
	use InterfaceTypeTrait;
}
