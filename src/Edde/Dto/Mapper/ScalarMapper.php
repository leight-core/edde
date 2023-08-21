<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Date\Mapper\IsoDateMapperTrait;
use Edde\Dto\SmartDto;
use Edde\Dto\Value;
use Edde\Schema\ISchemaLoader;
use Edde\Utils\Mapper\FloatMapperTrait;
use Edde\Utils\Mapper\IntBoolMapperTrait;
use Edde\Utils\Mapper\IntMapperTrait;

class ScalarMapper extends AbstractDtoMapper {
	use IntMapperTrait;
	use FloatMapperTrait;
	use IntBoolMapperTrait;
	use IsoDateMapperTrait;

	protected function handle(Value $value, SmartDto $dto) {
		$raw = $value->getRaw();
		switch ($value->getAttribute()->getType()) {
			case 'int':
			case 'integer':
				return $this->intMapper->item($raw);
			case 'float':
				return $this->floatMapper->item($raw);
			case ISchemaLoader::TYPE_BOOLINT:
				return $this->intBoolMapper->item($raw);
			case ISchemaLoader::TYPE_ISO_DATETIME:
				return $this->isoDateMapper->item($raw);
		}
		return $raw;
	}
}
