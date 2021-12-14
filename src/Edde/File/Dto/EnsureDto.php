<?php
declare(strict_types=1);

namespace Edde\File\Dto;

use Edde\Dto\AbstractDto;

class EnsureDto extends AbstractDto {
	/** @var string */
	public $path;
	/** @var string */
	public $name;
	/** @var string */
	public $mime;
	/** @var double|void */
	public $ttl;
	/** @var string */
	public $native;
	/** @var string|void */
	public $userId;
	/** @var double */
	public $size;
}
