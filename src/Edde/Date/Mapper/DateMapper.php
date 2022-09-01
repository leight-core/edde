<?php
declare(strict_types=1);

namespace Edde\Date\Mapper;

use DateTime;
use DateTimeInterface;
use Edde\Mapper\AbstractMapper;

class DateMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return isset($item['value']) ? (new DateTime($item['value']))->format($item['params']['format'] ?? DateTimeInterface::ATOM) : null;
	}
}
