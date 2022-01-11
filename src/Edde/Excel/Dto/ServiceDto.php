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
	 * @var string|null
	 * @description optional dto class of the service's handle method
	 */
	public $dto;
	/**
	 * @var string[]
	 * @description service-specific translations
	 */
	public $translations = [];
}
