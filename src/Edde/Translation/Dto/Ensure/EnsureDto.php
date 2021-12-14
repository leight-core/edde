<?php
declare(strict_types=1);

namespace Edde\Translation\Dto\Ensure;

use Edde\Dto\AbstractDto;
use Edde\Translation\Dto\Create\TranslationDto;

class EnsureDto extends AbstractDto {
	/** @var TranslationDto */
	public $translation;
}
