<?php
declare(strict_types=1);

namespace Edde\Mapper;

trait MapperUtilsTrait {
	public function isoDateNull($dateTime) {
		return $dateTime ? $dateTime->format(DATE_ISO8601) : null;
	}
}
