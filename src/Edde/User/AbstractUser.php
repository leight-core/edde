<?php
declare(strict_types=1);

namespace Edde\User;

use Edde\Dto\AbstractDto;

abstract class AbstractUser extends AbstractDto {
	/** @var string|null */
	public $id;
	/** @var string */
	public $name;
	/** @var string */
	public $login;
	/** @var string */
	public $site;
}
