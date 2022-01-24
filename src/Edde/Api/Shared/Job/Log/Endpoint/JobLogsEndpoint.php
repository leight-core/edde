<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Log\Endpoint;

use Edde\Job\Dto\Log\JobLogDto;
use Edde\Job\Dto\Log\JobLogFilterDto;
use Edde\Job\Dto\Log\JobLogOrderByDto;
use Edde\Job\Mapper\JobLogMapperTrait;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

class JobLogsEndpoint extends AbstractQueryEndpoint {
	use JobLogMapperTrait;
	use JobLogRepositoryTrait;

	/**
	 * @param Query<JobLogOrderByDto, JobLogFilterDto> $query
	 *
	 * @return QueryResult<JobLogDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->jobLogRepository->toResult($query, $this->jobLogMapper);
	}
}
