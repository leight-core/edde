<?php
declare(strict_types=1);

namespace Edde\Excel\Dto;

use Edde\Dto\AbstractDto;

/**
 * This DTO is used to handle whole Excel file; it expects all meta data availability like tab-to-service or
 * translation tab.
 */
class HandleDto extends AbstractDto {
	/**
	 * @var string
	 * @description file to handle
	 */
	public $file;
}
