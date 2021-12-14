<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

use Edde\Reflection\Dto\Type\AbstractType;

class ParameterResponseMethodDto extends ParameterMethodDto {
	/** @var AbstractType */
	public $response;
}
