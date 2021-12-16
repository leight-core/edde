<?php
declare(strict_types=1);

namespace Edde\User;

use Edde\Dto\AbstractDto;
use Edde\User\Dto\Settings\UserSettingsDto;

abstract class AbstractCurrentUser extends AbstractDto {
	/** @var string|void */
	public $id;
	/** @var string */
	public $name;
	/** @var string */
	public $login;
	/** @var string */
	public $site;
	/** @var UserSettingsDto|void */
	public $settings;
}
