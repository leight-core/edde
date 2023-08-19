<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Dto\Value;

class SchemaDtoMapper extends AbstractDtoMapper {
	use SmartServiceTrait;

	protected function handle(Value $value, SmartDto $dto) {
		$attribute = $value->getAttribute();
		$value = $dto->getValue(
			$attribute->getMetaOrThrow('source')
		);
		if (!$value) {
			return null;
		}
		return $this->smartService->from(
			$value,
			$attribute->getMetaOrThrow('schema')
		)->validate();
	}
}
