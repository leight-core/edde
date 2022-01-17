<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Api\Shared\Dto\DateTimeDto;
use Edde\Config\ConfigServiceTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;

/**
 * @description Returns a list of the available date-time formattings.
 */
class DateTimeFormatListEndpoint extends AbstractQueryEndpoint {
	use ConfigServiceTrait;

	const CONFIG_DATE_TIME_FORMATS = 'date-time-formats';

	/**
	 * @param Query $query
	 *
	 * @return QueryResult<DateTimeDto>
	 */
	public function post(Query $query): QueryResult {
		return $this->queryService->toResponse($this->configService->get(self::CONFIG_DATE_TIME_FORMATS, []), DateTimeDto::class);
	}
}
