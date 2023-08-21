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

	protected function handle(Value $value, SmartDto $dto) {
		$raw = $value->getRaw();
		switch ($value->getAttribute()->getType()) {
			case 'int':
			case 'integer':
				return $this->intMapper->item($raw);
			case 'float':
				return $this->floatMapper->item($raw);
			case self::TYPE_BOOLINT:
				return $this->intBoolMapper->item($raw);
			case self::TYPE_ISO_DATETIME:
				return $this->isoDateMapper->item($raw);
			case self::TYPE_JSON:
				return $this->jsonOutputMapper->item($raw);
		}
		return $raw;
	}
}
