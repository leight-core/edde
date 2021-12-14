<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Property;

use Edde\Reflection\Dto\Type\Utils\ITemplateType;
use Edde\Reflection\Dto\Type\Utils\TemplateTypeTrait;

class TemplateProperty extends AbstractProperty implements ITemplateType {
	use TemplateTypeTrait;
}
