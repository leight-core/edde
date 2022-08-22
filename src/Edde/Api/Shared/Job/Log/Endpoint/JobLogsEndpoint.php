<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Log\Endpoint;

use Edde\Job\Mapper\JobLogMapperTrait;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

class JobLogsEndpoint extends AbstractEndpoint {
	use JobLogMapperTrait;
	use JobLogRepositoryTrait;

	public function post(Query $query) {
		return $this->jobLogMapper->map($this->jobLogRepository->query($query));
	}
}
