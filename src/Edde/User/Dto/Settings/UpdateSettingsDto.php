<?php
declare(strict_types=1);

namespace Edde\User\Dto\Settings;

use Edde\Dto\AbstractDto;

class UpdateSettingsDto extends AbstractDto {
	/**
	 * @var UserSettingsDto
	 */
	public $settings;
}
