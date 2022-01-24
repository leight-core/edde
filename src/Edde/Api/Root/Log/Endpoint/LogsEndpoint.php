<?php
declare(strict_types=1);

namespace Edde\Api\Root\Log\Endpoint;

use Edde\Log\Dto\LogDto;
use Edde\Log\Dto\LogFilterDto;
use Edde\Log\Dto\LogOrderByDto;
use Edde\Log\Mapper\LogMapperTrait;
use Edde\Log\Repository\LogRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

/**
 * @description Page through system logs.
 */
class LogsEndpoint extends AbstractQueryEndpoint {
	use LogRepositoryTrait;
	use LogMapperTrait;

	/**
	 * @param Query<LogOrderByDto, LogFilterDto> $query
	 *
	 * @return QueryResult<LogDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->logRepository->toResult($query, $this->logMapper);
	}
}
