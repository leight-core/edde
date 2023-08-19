<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

use Edde\Job\Schema\Job\JobSchema;
use Edde\Mapper\AbstractMapper;

class JobMapper extends AbstractMapper {
	public function item($item, $params = null) {
		return $this->smartService->from($item, JobSchema::class);
	}
}
