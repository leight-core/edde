<?php
declare(strict_types=1);

namespace Edde\File\Dto;

use Edde\Dto\AbstractDto;

class GcResultDto extends AbstractDto {
	/**
	 * @var bool
	 * @description has GC run? In force mode true all the times
	 */
	public $hit;
	/**
	 * @var int
	 * @description how much database records (metadata) has been removed
	 */
	public $records;
	/**
	 * @var int
	 * @description number of removed files
	 */
	public $files;
	/**
	 * @var double
	 * @description how long the GC has been running
	 */
	public $runtime;
}
