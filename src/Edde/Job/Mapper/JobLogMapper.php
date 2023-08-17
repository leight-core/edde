<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

use Edde\Job\Schema\JobLog\JobLogSchema;
use Edde\Mapper\AbstractMapper;

class JobLogMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $this->smartService->from($item, JobLogSchema::class);
	}
}
