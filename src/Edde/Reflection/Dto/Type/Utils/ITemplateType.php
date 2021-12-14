<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

use Edde\Reflection\Dto\TemplateDto;

interface ITemplateType {
	public function template(): TemplateDto;
}
