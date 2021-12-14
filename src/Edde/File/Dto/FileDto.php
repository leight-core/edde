<?php
declare(strict_types=1);

namespace Edde\File\Dto;

use Edde\Dto\AbstractDto;
use Marsh\User\Dto\UserDto;

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
	/** @var string|void|null */
	public $updated;
	/** @var double|null|void */
	public $ttl;
	/** @var UserDto|null|void */
	public $user;
}
