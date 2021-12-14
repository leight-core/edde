<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto;

use Edde\Dto\AbstractDto;

class ConstantDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var mixed
	 */
	public $value;
}
