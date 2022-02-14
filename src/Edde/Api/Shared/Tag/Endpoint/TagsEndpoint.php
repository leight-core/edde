<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Tag\Endpoint;

use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;
use Edde\Tag\Dto\TagDto;
use Edde\Tag\Dto\TagFilterDto;
use Edde\Tag\Dto\TagOrderByDto;
use Edde\Tag\Mapper\TagMapperTrait;
use Edde\Tag\Repository\TagRepositoryTrait;

class TagsEndpoint extends AbstractQueryEndpoint {
	use TagRepositoryTrait;
	use TagMapperTrait;

	/**
	 * @param Query<TagOrderByDto, TagFilterDto> $query
	 *
	 * @return QueryResult<TagDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->tagRepository->toResult($query, $this->tagMapper);
	}
}
