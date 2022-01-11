<?php
declare(strict_types=1);

namespace Edde\Excel\Dto;

use Edde\Dto\AbstractDto;

class ReadDto extends AbstractDto {
	/**
	 * @var string
	 * @description file to be loaded
	 */
	public $file;
	/**
	 * @var mixed|void
	 * @description name of sheets to be loaded; this may save some performance; also keep in mind when this options is used, $worksheet index may change
	 */
	public $sheets;
	/**
	 * @var int|void
	 * @description worksheet index to be iterated; if a name has been specified, this should be 0
	 */
	public $worksheet = 0;
	/**
	 * @var int|void
	 * @description number of rows to skip
	 */
	public $skip = 1;
	/**
	 * @var string[]
	 * @description custom translations for this reading
	 */
	public $translations = [];
}
