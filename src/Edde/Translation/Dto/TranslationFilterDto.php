<?php
declare(strict_types=1);

namespace Edde\Translation\Dto;

use Edde\Repository\Dto\AbstractFilterDto;

class TranslationFilterDto extends AbstractFilterDto {
	/** @var string|void */
	public $locale;
	/** @var string|void */
	public $key;
	/** @var string|void */
	public $translation;
}
