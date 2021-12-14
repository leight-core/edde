<?php
declare(strict_types=1);

namespace Edde\Translation\Dto;

use Edde\Dto\AbstractDto;

class TranslationsDto extends AbstractDto {
	/**
	 * @var TranslationDto[]
	 * @description all available translations
	 */
	public $translations;
}
