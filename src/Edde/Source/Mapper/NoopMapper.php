<?php
declare(strict_types=1);

namespace Edde\Source\Mapper;

use Edde\Mapper\AbstractMapper;

class NoopMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $item['value'] ?? null;
	}
}
