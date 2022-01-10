<?php
declare(strict_types=1);

namespace Edde\Excel\Dto;

use Edde\Dto\AbstractDto;

class ServiceDto extends AbstractDto {
	/**
	 * @var string
	 * @description service name
	 */
	public $name;
	/**
	 * @var string[]
	 * @description service-specific translations
	 */
	public $translations = [];
}
