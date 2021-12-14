<?php
declare(strict_types=1);

namespace Edde\Import\Template\Dto;

use Edde\Dto\AbstractDto;
use Edde\File\Dto\FileDto;

class ImportTemplateDto extends AbstractDto {
	/**
	 * @var string
	 */
	public $id;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var string
	 */
	public $hash;
	/**
	 * @var string
	 */
	public $fileId;
	/**
	 * @var FileDto
	 */
	public $file;
}
