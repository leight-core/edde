<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Dto\SmartDto;
use Edde\Dto\Value;
use Edde\Utils\Mapper\JsonInputMapperTrait;

class InputTypeMapper extends AbstractDtoMapper implements ITypeMapper {
	use JsonInputMapperTrait;

	protected function handle($item, Value $value, SmartDto $dto) {
        if ($value->getAttribute()->isArray()) {
            return $item;
        }
		switch ($value->getAttribute()->getType()) {
			case self::TYPE_JSON:
				return $this->jsonInputMapper->item($item);
		}
		return $item;
	}
}
