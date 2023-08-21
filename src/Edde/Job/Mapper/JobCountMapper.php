<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

use Edde\Dto\Mapper\AbstractDtoMapper;
use Edde\Dto\SmartDto;
use Edde\Dto\Value;

class JobCountMapper extends AbstractDtoMapper {
	protected function handle($item, Value $value, SmartDto $dto) {
		return $dto->getValue('successCount') + $dto->getValue('errorCount') + $dto->getValue('skipCount');
	}
}
