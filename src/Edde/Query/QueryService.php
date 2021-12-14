<?php
declare(strict_types=1);

namespace Edde\Query;

use Edde\Dto\DtoServiceTrait;
use Edde\Mapper\IMapper;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Repository\IRepository;
use function array_map;

class QueryService {
	use DtoServiceTrait;

	public function source(Query $query, IRepository $repository, IMapper $mapper): QueryResult {
		return new QueryResult(
			$repository->total($query),
			$query->size,
			$mapper->map($repository->query($query) ?? [])
		);
	}

	public function query(Query $query, IRepository $repository, IMapper $mapper): QueryResult {
		return $this->source($query, $repository, $mapper);
	}

	public function toResponse(array $items, string $source = null): QueryResult {
		return new QueryResult(count($items), 10, $source ? array_map(function (array $item) use ($source) {
			return $this->dtoService->fromArray($source, $item);
		}, $items) : $items);
	}
}
