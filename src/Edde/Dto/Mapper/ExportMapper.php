<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Mapper\AbstractMapper;
use Edde\Utils\StringUtils;

class ExportMapper extends AbstractMapper {
	const META = 'export-mapper';
	const CONVERT_CAMEL = 0;

	public function item($item, $params = null) {
		if (!$item) {
			return null;
		} else if (!($item instanceof SmartDto)) {
			throw new SmartDtoException(sprintf('[%s] mapper supports only SmartDto instances (%s); input is [%s].', static::class, SmartDto::class, gettype($item)));
		}
		$map = $item->getSchema()->getMeta(static::META, []);
		$export = [];
		foreach ($item->export($params['raw'] ?? false) as $k => $value) {
			$key = $k;
			if (array_key_exists($k, $map)) {
				switch ($kk = $map[$k]) {
					case self::CONVERT_CAMEL:
						$key = StringUtils::recamel($key, '_');
						break;
					default:
						$key = $kk;
				}
			}
			$export[$key] = $value;
		}
		return (object)$export;
	}
}
