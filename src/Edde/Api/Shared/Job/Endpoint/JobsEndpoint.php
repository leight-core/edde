<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Endpoint;

use Edde\Job\Dto\JobDto;
use Edde\Job\Dto\JobFilterDto;
use Edde\Job\Dto\JobOrderByDto;
use Edde\Job\Mapper\JobMapperTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

class JobsEndpoint extends AbstractQueryEndpoint {
	use JobRepositoryTrait;
	use JobMapperTrait;

	/**
	 * @param Query<JobOrderByDto, JobFilterDto> $query
	 *
	 * @return QueryResult<JobDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->jobRepository->toResult($query, $this->jobMapper);
	}
}
