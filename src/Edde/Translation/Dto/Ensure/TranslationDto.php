<?php
declare(strict_types=1);

namespace Edde\Translation\Dto\Ensure;

use Edde\Dto\AbstractDto;

class TranslationDto extends AbstractDto {
	/** @var string */
	public $locale;
	/** @var string */
	public $key;
	/** @var string */
	public $translation;
}
