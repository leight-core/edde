<?php
declare(strict_types=1);

namespace Edde\Api\Root\Profiler\Endpoint;

use Edde\Profiler\Mapper\ProfilerMapperTrait;
use Edde\Profiler\Repository\ProfilerRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

class ProfilersEndpoint extends AbstractEndpoint {
	use ProfilerRepositoryTrait;
	use ProfilerMapperTrait;

	public function post(Query $query) {
		return $this->profilerMapper->map($this->profilerRepository->query($query));
	}
}
