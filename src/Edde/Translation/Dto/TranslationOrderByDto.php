<?php
declare(strict_types=1);

namespace Edde\Translation\Dto;

use Edde\Repository\Dto\AbstractOrderByDto;

class TranslationOrderByDto extends AbstractOrderByDto {
	/** @var bool|void */
	public $locale;
	/** @var bool|void */
	public $key;
	/** @var bool|void */
	public $translation;
}
