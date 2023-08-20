<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

use Edde\Dto\Mapper\AbstractPushOfMapper;
use Edde\Job\Schema\Job\JobSchema;

class JobDtoMapper extends AbstractPushOfMapper {
	public function getSchema(): string {
		return JobSchema::class;
	}
}
