<?php
declare(strict_types=1);

namespace Edde\Discovery\Dto;

use Edde\Dto\AbstractDto;

class DiscoveryIndexDto extends AbstractDto {
	/**
	 * @var DiscoveryItemDto[]
	 */
	public $index;
}
