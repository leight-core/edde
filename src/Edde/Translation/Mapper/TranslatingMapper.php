<?php
declare(strict_types=1);

namespace Edde\Translation\Mapper;

use Dibi\Exception as DibiException;
use Edde\Mapper\AbstractMapper;
use Edde\Translation\TranslationServiceTrait;
use Marsh\User\Exception\UserNotSelectedException;

class TranslatingMapper extends AbstractMapper {
	use TranslationServiceTrait;

	/**
	 * @param       $item
	 * @param array $params
	 *
	 * @return array|false
	 *
	 * @throws DibiException
	 * @throws UserNotSelectedException
	 */
	public function item($item, array $params = []) {
		return array_combine(array_map(function (string $label) use ($params) {
			return $this->translationService->translation(($params['service'] ?? static::class) . ($params['prefix'] ? '.' . $params['prefix'] : '') . '.' . $label, $label);
		}, array_keys($item)), array_values($item));
	}
}
