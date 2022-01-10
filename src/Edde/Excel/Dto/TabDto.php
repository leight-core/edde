<?php
declare(strict_types=1);

namespace Edde\Excel\Dto;

use Edde\Dto\AbstractDto;

class TabDto extends AbstractDto {
	/**
	 * @var string
	 * @description translated tab name
	 */
	public $name;
	/**
	 * @var string[]
	 * @description an array of services used to process the given tab
	 */
	public $services;
}
