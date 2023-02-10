<?php
declare(strict_types=1);

namespace Edde\Utils;

use DateTime;

class DateUtils {
	static public function date(?string $date): ?DateTime {
		return $date ? (new DateTime($date))->setTime(0, 0) : null;
	}

	static public function today(): DateTime {
		return new DateTime('today');
	}
}
