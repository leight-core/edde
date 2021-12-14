<?php
declare(strict_types=1);

namespace Edde\File\Dto;

use Edde\Dto\AbstractDto;

class CommitDto extends AbstractDto {
	/**
	 * @var string
	 * @description uuid of the uploaded chunks (file)
	 */
	public $uuid;
	/**
	 * @var string
	 * @description path where the file will live
	 */
	public $path;
	/**
	 * @var string|void
	 * @description optional filename; when null/undefined, random uuid will be used
	 */
	public $name;
}
