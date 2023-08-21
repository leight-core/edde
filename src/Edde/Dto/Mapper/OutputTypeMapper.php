<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Date\Mapper\IsoDateMapperTrait;
use Edde\Dto\SmartDto;
use Edde\Dto\Value;
use Edde\Utils\Mapper\FloatMapperTrait;
use Edde\Utils\Mapper\IntBoolMapperTrait;
use Edde\Utils\Mapper\IntMapperTrait;
use Edde\Utils\Mapper\JsonOutputMapperTrait;

class OutputTypeMapper extends AbstractDtoMapper implements ITypeMapper {
	use IntMapperTrait;
	use FloatMapperTrait;
	use IntBoolMapperTrait;
	use IsoDateMapperTrait;
	use JsonOutputMapperTrait;

	protected function handle($item, Value $value, SmartDto $dto) {
		switch ($value->getAttribute()->getType()) {
			case 'int':
			case 'integer':
			return $this->intMapper->item($item);
			case 'float':
				return $this->floatMapper->item($item);
			case self::TYPE_BOOLINT:
				return $this->intBoolMapper->item($item);
			case self::TYPE_ISO_DATETIME:
				return $this->isoDateMapper->item($item);
			case self::TYPE_JSON:
				return $this->jsonOutputMapper->item($item);
		}
		return $item;
	}
}
