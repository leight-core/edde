<?php
declare(strict_types=1);

namespace Edde\User;

use Edde\Dto\AbstractDto;

abstract class AbstractUser extends AbstractDto {
	/** @var string */
	public $id;
	/** @var string */
	public $name;
}
