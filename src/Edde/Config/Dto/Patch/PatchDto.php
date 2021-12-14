<?php
declare(strict_types=1);

namespace Edde\Config\Dto\Patch;

use Edde\Dto\AbstractDto;

class PatchDto extends AbstractDto {
	/**
	 * @var ConfigDto
	 */
	public $config;
	/**
	 * @var string
	 */
	public $id;
}
