<?php
declare(strict_types=1);

namespace Edde\Rest\Endpoint;

use Edde\Mapper\IMapper;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Query\QueryServiceTrait;
use Edde\Repository\IRepository;
use Edde\Rest\IQueryEndpoint;

abstract class AbstractQueryEndpoint extends AbstractEndpoint implements IQueryEndpoint {
	use QueryServiceTrait;

	protected function query(Query $query, IRepository $repository, IMapper $mapper): QueryResult {
		return $this->queryService->query($query, $repository, $mapper);
	}
}
