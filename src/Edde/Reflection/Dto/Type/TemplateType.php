<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type;

use Edde\Reflection\Dto\Type\Utils\ITemplateType;
use Edde\Reflection\Dto\Type\Utils\TemplateTypeTrait;

/**
 * Template type references to type from it's class (meaning it's a generic type).
 */
class TemplateType extends AbstractType implements ITemplateType {
	use TemplateTypeTrait;
}
