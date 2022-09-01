<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Translation\Dto\TranslationDto;

class ToTranslationMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $this->dtoService->fromArray(TranslationDto::class, [
			'id'        => $item->id,
			'language'  => $item->locale,
			'label'     => $item->key,
			'text'      => $item->translation,
			'namespace' => 'translation',
		]);
	}
}
