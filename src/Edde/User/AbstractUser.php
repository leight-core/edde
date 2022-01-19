<?php
declare(strict_types=1);

namespace Edde\User;

use Edde\Dto\AbstractDto;
use Edde\Role\Dto\RoleDto;
use Edde\User\Dto\Settings\UserSettingsDto;

abstract class AbstractUser extends AbstractDto {
	/** @var string */
	public $id;
	/** @var string */
	public $name;
	/** @var string */
	public $email;
	/** @var string|null */
	public $site;
	/** @var UserSettingsDto|void */
	public $settings;
	/** @var RoleDto[] */
	public $roles = [];
}
