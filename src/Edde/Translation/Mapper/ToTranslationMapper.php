<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

use Edde\Mapper\AbstractMapper;

class ToTranslationMapper extends AbstractMapper {
	public function item($item, array $params = []) {
		return [
			'id'        => $item->id,
			'language'  => $item->locale,
			'label'     => $item->key,
			'text'      => $item->translation,
			'namespace' => 'translation',
		];
	}
}
