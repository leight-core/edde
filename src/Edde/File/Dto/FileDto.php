<?php
declare(strict_types=1);

namespace Edde\File\Dto;

use Edde\Bridge\User\UserDto;
use Edde\Dto\AbstractDto;

class FileDto extends AbstractDto {
	/** @var string */
	public $id;
	/** @var string */
	public $path;
	/** @var string */
	public $name;
	/** @var string */
	public $mime;
	/** @var int */
	public $size;
	/** @var string */
	public $native;
	/** @var string */
	public $created;
	/** @var string|void */
	public $updated;
	/** @var double|void */
	public $ttl;
	/** @var UserDto|void */
	public $user;
}
