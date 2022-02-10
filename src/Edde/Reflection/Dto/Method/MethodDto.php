<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Method;

use Edde\Dto\AbstractDto;

class MethodDto extends AbstractDto {
	/** @var string */
	public $name;
	/** @var array */
	public $annotations = [];
}
