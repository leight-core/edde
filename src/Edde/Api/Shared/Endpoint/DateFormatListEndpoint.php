<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Endpoint;

use Edde\Config\ConfigServiceTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

/**
 * @description Return list of available date formattings.
 */
class DateFormatListEndpoint extends AbstractEndpoint {
	use ConfigServiceTrait;

	const CONFIG_DATE_FORMATS = 'date-formats';

	public function post(Query $query) {
		return $this->configService->get(self::CONFIG_DATE_FORMATS, []);
	}
}
