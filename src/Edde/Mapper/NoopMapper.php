<?php
declare(strict_types=1);

namespace Edde\Mapper;

class NoopMapper extends AbstractMapper {
	public function item($item, array $params = []) {
		return $item;
	}
}
