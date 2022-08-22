<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Log\Endpoint;

use Edde\Progress\IProgress;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;

class LevelsEndpoint extends AbstractEndpoint {
	public function post(Query $query) {
		return [
			[
				'level' => IProgress::LOG_INFO,
				'label' => 'info',
			],
			[
				'level' => IProgress::LOG_WARNING,
				'label' => 'warning',
			],
			[
				'level' => IProgress::LOG_ERROR,
				'label' => 'error',
			],
		];
	}
}
