<?php
declare(strict_types=1);

namespace Edde\Mapper;

use DateTime;
use DateTimeZone;
use function is_string;
use const DATE_ATOM;

trait MapperUtilsTrait {
	public function isoDateNull($dateTime) {
		if (is_string($dateTime)) {
			$dateTime = new DateTime($dateTime);
		}
		return $dateTime ? $dateTime->setTimezone(new DateTimeZone('UTC'))->format(DATE_ATOM) : null;
	}
}
