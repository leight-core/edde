<?php
declare(strict_types=1);

namespace Edde\User;

use DateTime;
use Edde\Dto\AbstractDto;
use Edde\Role\Dto\RoleDto;
use Edde\User\Dto\Settings\UserSettingsDto;
use function is_string;

abstract class AbstractCurrentUser extends AbstractDto {
	/** @var string */
	public $id;
	/** @var string */
	public $name;
	/** @var string */
	public $email;
	/** @var string */
	public $site;
	/** @var UserSettingsDto|void */
	public $settings;
	/** @var RoleDto[] */
	public $roles = [];

	public function toLocalDate($date): ?string {
		if (!$date) {
			return null;
		}
		if (is_string($date)) {
			$date = new DateTime($date);
		}
		return $date->format($this->settings->date ?? DateTime::ATOM);
	}
}
