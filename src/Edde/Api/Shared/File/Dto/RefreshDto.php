<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File\Dto;

use Edde\Dto\AbstractDto;

class RefreshDto extends AbstractDto {
	/** @var string */
	public $fileId;
}
