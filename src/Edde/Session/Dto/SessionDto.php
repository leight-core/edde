<?php
declare(strict_types=1);

namespace Edde\Session\Dto;

use Edde\Bridge\User\UserDto;
use Edde\Dto\AbstractDto;

class SessionDto extends AbstractDto {
	/**
	 * @var UserDto
	 * @description An user, regardless of a login status (that means a public user is also an user)
	 */
	public $user;
}
