<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type;

use Edde\Reflection\Dto\Type\Utils\IInterfaceType;
use Edde\Reflection\Dto\Type\Utils\InterfaceTypeTrait;

class InterfaceType extends AbstractType implements IInterfaceType {
	use InterfaceTypeTrait;
}
