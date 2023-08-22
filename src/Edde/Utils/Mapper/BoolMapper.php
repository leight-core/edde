<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

use Edde\Mapper\AbstractMapper;

class BoolMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return filter_var($item, FILTER_VALIDATE_BOOLEAN);
	}
}
