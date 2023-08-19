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
		return $this->smartService->from(
			$dto->getValue(
				$attribute->getMetaOrThrow('source')
			),
			$attribute->getMetaOrThrow('schema')
		);
	}
}
