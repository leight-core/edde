<?php
declare(strict_types=1);

namespace Edde\Api\Root\Profiler\Endpoint;

use Edde\Profiler\Dto\ProfilerDto;
use Edde\Profiler\Dto\ProfilerFilterDto;
use Edde\Profiler\Dto\ProfilerOrderByDto;
use Edde\Profiler\Mapper\ProfilerMapperTrait;
use Edde\Profiler\Repository\ProfilerRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

class ProfilersEndpoint extends AbstractQueryEndpoint {
	use ProfilerRepositoryTrait;
	use ProfilerMapperTrait;

	/**
	 * @param Query<ProfilerOrderByDto, ProfilerFilterDto> $query
	 *
	 * @return QueryResult<ProfilerDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->profilerRepository->toResult($query, $this->profilerMapper);
	}
}
