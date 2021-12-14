<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Parameter;

use Edde\Reflection\Dto\Type\AbstractType;

abstract class AbstractParameter extends AbstractType {
	/**
	 * @var string
	 * @description parameter name
	 */
	public $name;
	/**
	 * @var bool
	 */
	public $isVariadic;
}
