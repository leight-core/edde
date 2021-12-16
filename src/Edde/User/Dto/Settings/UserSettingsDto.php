<?php
declare(strict_types=1);

namespace Edde\User\Dto\Settings;

use Edde\Dto\AbstractDto;

class UserSettingsDto extends AbstractDto {
	/**
	 * @var string|void
	 * @description Language of text displayed to used (not directly a locale).
	 */
	public $language;
	/**
	 * @var string|void
	 * @description Date formatting (explicitly set, ignoring locale).
	 */
	public $date;
	/**
	 * @var string|void
	 * @description Date-time formatting (explicitly set, ignoring locale).
	 */
	public $datetime;
}
