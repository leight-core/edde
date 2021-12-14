<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto;

use Edde\Dto\AbstractDto;

/**
 * Template is coming from Class - it's a generic definition (like `Foo<this, one=void>`).
 */
class TemplateDto extends AbstractDto {
	/** @var string */
	public $name;
	/** @var string|null */
	public $default;
}
