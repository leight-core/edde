<?php
declare(strict_types=1);

namespace Edde\User;

use Edde\Dto\AbstractDto;
use Edde\Role\Dto\RoleDto;
use Edde\User\Dto\Settings\UserSettingsDto;

abstract class AbstractUser extends AbstractDto {
	/** @var string */
	public string $id;
	/** @var string */
	public string $name;
	/** @var string */
	public string $email;
	/** @var string */
	public string $site;
	/** @var UserSettingsDto|void */
	public $settings;
	/** @var RoleDto[] */
	public array $roles = [];
}
