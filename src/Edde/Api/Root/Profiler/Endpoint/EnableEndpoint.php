<?php
declare(strict_types=1);

namespace Edde\Api\Root\Profiler\Endpoint;

use Edde\Profiler\ProfilerServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class EnableEndpoint extends AbstractMutationEndpoint {
	use ProfilerServiceTrait;

	public function post() {
		$this->profilerService->enable();
	}
}
