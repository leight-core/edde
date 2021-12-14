<?php
declare(strict_types=1);

namespace Edde\Dto\Common;

use Edde\Dto\AbstractDto;

class LoginRequest extends AbstractDto {
	/**
	 * @var string
	 */
	public $login;
	/**
	 * @var string
	 */
	public $password;
}
