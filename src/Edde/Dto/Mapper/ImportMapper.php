<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Dto\Exception\SmartDtoException;
use Edde\Mapper\AbstractMapper;
use Edde\Schema\ISchema;
use Edde\Utils\StringUtils;

class ImportMapper extends AbstractMapper {
	const META = 'import-mapper';
	const CONVERT_KEEP = 0;
	const CONVERT_CAMEL = 1;

	public function item($item, $params = null) {
		if (!$item) {
			return null;
		} else if (!isset($params['schema'])) {
			throw new SmartDtoException('Missing [schema => ISchema] parameter.');
		}
		/** @var $schema ISchema */
		$schema = $params['schema'];
		$map = $schema->getMeta(static::META, []);
		$export = [];
		foreach ($item as $k => $value) {
			$key = $k;
			switch ($map[$k] ?? false) {
				case self::CONVERT_CAMEL:
					$key = StringUtils::toCamelHump($key);
					break;
			}
			$export[$key] = $value;
		}
		return $this->smartService->from($export, $schema->getName());
	}
}
