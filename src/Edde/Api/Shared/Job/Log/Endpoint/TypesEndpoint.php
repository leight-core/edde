<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Log\Endpoint;

use Edde\Job\Dto\Log\LogTypeDto;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

class TypesEndpoint extends AbstractQueryEndpoint {
	/**
	 * @param Query $query
	 *
	 * @return QueryResult<LogTypeDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->queryService->toResponse([
			[
				'id'   => 'vehicle.recovery',
				'type' => 'vehicle.recovery',
			],
			[
				'id'   => 'common',
				'type' => null,
			],
		], LogTypeDto::class);
	}
}
