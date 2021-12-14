<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Parameter;

use Edde\Reflection\Dto\Type\Utils\ITemplateType;
use Edde\Reflection\Dto\Type\Utils\TemplateTypeTrait;

class TemplateParameter extends AbstractParameter implements ITemplateType {
	use TemplateTypeTrait;
}
