<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Log\Endpoint;

use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

class TypesEndpoint extends AbstractEndpoint {
	public function post(Query $query) {
		return [
			[
				'id'   => 'vehicle.recovery',
				'type' => 'vehicle.recovery',
			],
			[
				'id'   => 'common',
				'type' => null,
			],
		];
	}
}
