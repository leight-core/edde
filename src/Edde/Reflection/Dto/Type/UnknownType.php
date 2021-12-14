<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type;

use Edde\Reflection\Dto\Type\Utils\IUnknownType;
use Edde\Reflection\Dto\Type\Utils\UnknownTypeTrait;

class UnknownType extends AbstractType implements IUnknownType {
	use UnknownTypeTrait;
}
