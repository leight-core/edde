<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Parameter;

use Edde\Reflection\Dto\Type\Utils\IInterfaceType;
use Edde\Reflection\Dto\Type\Utils\InterfaceTypeTrait;

class InterfaceParameter extends AbstractParameter implements IInterfaceType {
	use InterfaceTypeTrait;
}
