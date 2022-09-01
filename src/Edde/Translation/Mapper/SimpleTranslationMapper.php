<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

use Edde\Mapper\AbstractMapper;

class SimpleTranslationMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return [
			'key'   => $item->key,
			'value' => $item->translation,
		];
	}
}
