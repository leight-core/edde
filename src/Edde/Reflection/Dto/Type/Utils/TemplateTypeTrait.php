<?php
declare(strict_types=1);

namespace Edde\Reflection\Dto\Type\Utils;

use Edde\Reflection\Dto\TemplateDto;

trait TemplateTypeTrait {
	/**
	 * @var TemplateDto
	 */
	public $template;

	public function template(): TemplateDto {
		return $this->template;
	}
}
