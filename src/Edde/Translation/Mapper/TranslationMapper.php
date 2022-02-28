<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Translation\TranslationServiceTrait;

class TranslationMapper extends AbstractMapper {
	use TranslationServiceTrait;

	public function item($item) {
		return isset($item['value']) ?
			$this->translationService->translate((isset($item['params']['prefix']) ? $item['params']['prefix'] . '.' : '') . $item['value'], $item['params']['language'] ?? 'en', $item['value']) :
			null;
	}
}
