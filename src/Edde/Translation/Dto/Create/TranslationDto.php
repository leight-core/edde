<?php
declare(strict_types=1);

namespace Edde\Translation\Dto\Create;

use Edde\Dto\AbstractDto;

class TranslationDto extends AbstractDto {
	/** @var string */
	public $language;
	/** @var string */
	public $label;
	/** @var string */
	public $translation;
}
