<?php
declare(strict_types=1);

namespace Edde\Excel\Dto;

use Edde\Dto\AbstractDto;

class MetaDto extends AbstractDto {
	/**
	 * @var string
	 * @description source file of this meta dto
	 */
	public $file;
	/**
	 * @var int
	 * @description grand total of all items available
	 */
	public $total = 0;
	/**
	 * @var TabDto[]
	 * @description tab definition for process
	 */
	public $tabs = [];
	/**
	 * @var string[]
	 * @description file-wide translations (like tab names or headers)
	 */
	public $translations;
	/**
	 * @var ServiceDto[]
	 * @description services required by the requested file
	 */
	public $services;
}
