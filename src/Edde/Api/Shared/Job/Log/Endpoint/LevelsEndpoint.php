<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Log\Endpoint;

use Edde\Job\Dto\Log\LogLevelDto;
use Edde\Progress\IProgress;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

class LevelsEndpoint extends AbstractQueryEndpoint {
	/**
	 * @param Query $query
	 *
	 * @return QueryResult<LogLevelDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->queryService->toResponse([
			[
				'level' => IProgress::LOG_INFO,
				'label' => 'info',
			],
			[
				'level' => IProgress::LOG_WARNING,
				'label' => 'warning',
			],
			[
				'level' => IProgress::LOG_ERROR,
				'label' => 'error',
			],
		], LogLevelDto::class);
	}
}
