<?php
declare(strict_types=1);

namespace Edde\Source\Dto;

use Edde\Dto\AbstractDto;

class SourcesDto extends AbstractDto {
	/**
	 * @var SourceDto[]
	 */
	public $sources = [];
}
