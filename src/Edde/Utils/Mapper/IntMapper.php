<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

use Edde\Mapper\AbstractMapper;

class IntMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $item !== null ? (int)$item : null;
	}
}
