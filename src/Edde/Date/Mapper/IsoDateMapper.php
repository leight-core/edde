<?php
declare(strict_types=1);

namespace Edde\Date\Mapper;

use Edde\Mapper\AbstractMapper;

class IsoDateMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $this->isoDateNull($item);
	}
}
