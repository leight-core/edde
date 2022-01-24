<?php
declare(strict_types=1);

namespace Edde\Api\Root\Log\Endpoint;

use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;
use Edde\Tag\Dto\TagDto;
use Edde\Tag\Mapper\TagMapperTrait;
use Edde\Tag\Repository\TagRepositoryTrait;

/**
 * @description Return tags available for filtering logs.
 */
class LogTagsEndpoint extends AbstractQueryEndpoint {
	use TagRepositoryTrait;
	use TagMapperTrait;

	/**
	 * @param Query $query
	 *
	 * @return QueryResult<TagDto>
	 */
	public function post(Query $query): QueryResult {
		/**
		 * We're not expecting input here; this endpoint should be moved to general space and group has to be
		 * extracted as a regular filter.
		 *
		 * @TODO('move to root/log with parametrized group')
		 */
		$query->filter = (object)['group' => 'log'];
		return $this->queryService->query($query, $this->tagRepository, $this->tagMapper);
	}
}
