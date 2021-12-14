<?php
declare(strict_types=1);

namespace Edde\Translation\Dto;

use Edde\Dto\AbstractDto;

class TranslationDto extends AbstractDto {
	/** @var string */
	public $id;
	/** @var string */
	public $language;
	/** @var string */
	public $namespace;
	/** @var string */
	public $label;
	/** @var string */
	public $text;
}
