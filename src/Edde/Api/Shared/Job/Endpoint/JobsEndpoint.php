<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Endpoint;

use Edde\Job\Mapper\JobMapperTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

class JobsEndpoint extends AbstractEndpoint {
	use JobRepositoryTrait;
	use JobMapperTrait;

	public function post(Query $query) {
		return $this->jobMapper->map($this->jobRepository->query($query));
	}
}
