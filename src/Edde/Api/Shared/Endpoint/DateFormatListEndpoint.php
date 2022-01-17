<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Api\Shared\Dto\DateDto;
use Edde\Config\ConfigServiceTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

/**
 * @description Return list of available date formattings.
 */
class DateFormatListEndpoint extends AbstractQueryEndpoint {
	use ConfigServiceTrait;

	const CONFIG_DATE_FORMATS = 'date-formats';

	/**
	 * @param Query $query
	 *
	 * @return QueryResult<DateDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->queryService->toResponse($this->configService->get(self::CONFIG_DATE_FORMATS, []), DateDto::class);
	}
}
