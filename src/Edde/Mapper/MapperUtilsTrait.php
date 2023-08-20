<?php
declare(strict_types=1);

namespace Edde\Mapper;

use DateTime;
use DateTimeZone;
use function is_string;

trait MapperUtilsTrait {
	public function isoDateNull($dateTime) {
		if (is_string($dateTime)) {
			$dateTime = new DateTime($dateTime, new DateTimeZone('UTC'));
		}
		return $dateTime ? $dateTime->format('Y-m-d H:i:s') : null;
	}
}
