<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Parameter;

use Edde\Reflection\Dto\Type\Utils\IUnknownType;
use Edde\Reflection\Dto\Type\Utils\UnknownTypeTrait;

class UnknownParameter extends AbstractParameter implements IUnknownType {
	use UnknownTypeTrait;
}
