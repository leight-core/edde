<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type;

use Edde\Reflection\Dto\Type\Utils\IScalarType;
use Edde\Reflection\Dto\Type\Utils\ScalarTypeTrait;

/**
 * Scalar type is basically PHP's scalar type (without an array).
 */
class ScalarType extends AbstractType implements IScalarType {
	use ScalarTypeTrait;
}
