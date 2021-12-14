<?php
declare(strict_types=1);

namespace Edde\File\Dto;

use Edde\Repository\Dto\AbstractOrderByDto;

class FileOrderByDto extends AbstractOrderByDto {
	/** @var bool|void */
	public $path;
	/** @var bool|void */
	public $name;
	/** @var bool|void */
	public $native;
	/** @var bool|void */
	public $mime;
	/** @var bool|void */
	public $size;
	/** @var bool|void */
	public $userId;
	/** @var bool|void */
	public $ttl;
	/** @var bool|void */
	public $created;
	/** @var bool|void */
	public $updated;
}
