<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Config\ConfigServiceTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

/**
 * @description Returns a list of the available date-time formattings.
 */
class DateTimeFormatListEndpoint extends AbstractEndpoint {
	use ConfigServiceTrait;

	const CONFIG_DATE_TIME_FORMATS = 'date-time-formats';

	public function post(Query $query) {
		return $this->configService->get(self::CONFIG_DATE_TIME_FORMATS, []);
	}
}
