<?php
declare(strict_types=1);

namespace Edde\File\Dto;

use Edde\Repository\Dto\AbstractFilterDto;

class FileFilterDto extends AbstractFilterDto {
	/** @var string[]|void */
	public $userIds;
	/** @var string[]|void */
	public $paths;
	/** @var string|void */
	public $path;
	/** @var string[]|void */
	public $mimes;
}
