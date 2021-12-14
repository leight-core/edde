<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Property;

use Edde\Reflection\Dto\Type\AbstractType;

abstract class AbstractProperty extends AbstractType {
	/**
	 * @var string
	 * @description property name
	 */
	public $name;
}
