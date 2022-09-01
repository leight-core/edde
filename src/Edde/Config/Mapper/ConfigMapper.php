<?php
declare(strict_types=1);

namespace Edde\Config\Mapper;

use Edde\Config\Dto\ConfigDto;
use Edde\Mapper\AbstractMapper;

class ConfigMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return ConfigDto::create([
			'id'    => $item->id,
			'key'   => $item->key,
			'value' => $item->value,
		]);
	}
}
