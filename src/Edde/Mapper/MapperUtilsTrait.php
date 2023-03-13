<?php
declare(strict_types=1);

namespace Edde\Mapper;

use DateTime;
use function is_string;
use const DATE_ATOM;

trait MapperUtilsTrait {
	public function isoDateNull($dateTime) {
		if (is_string($dateTime)) {
			$dateTime = new DateTime($dateTime, 'UTC');
		}
		return $dateTime ? $dateTime->format(DATE_ATOM) : null;
	}
}
