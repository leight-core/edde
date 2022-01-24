<?php
declare(strict_types=1);

namespace Edde\Api\Root\Profiler\Endpoint;

use Dibi\Row;
use Edde\Profiler\Dto\ProfilerNameDto;
use Edde\Profiler\Repository\ProfilerRepositoryTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;
use function array_map;
use function iterator_to_array;

class NamesEndpoint extends AbstractQueryEndpoint {
	use ProfilerRepositoryTrait;

	/**
	 * @param Query $query
	 *
	 * @return QueryResult<ProfilerNameDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->queryService->toResponse(array_map(function (Row $row) {
			return [
				'id'   => $row->name,
				'name' => $row->name,
			];
		}, iterator_to_array($this->profilerRepository->names())), ProfilerNameDto::class);
	}
}
