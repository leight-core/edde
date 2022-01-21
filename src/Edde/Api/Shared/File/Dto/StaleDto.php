<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Dto;

use Edde\Dto\AbstractDto;

class StaleDto extends AbstractDto {
	/** @var string */
	public string $fileId;
}
