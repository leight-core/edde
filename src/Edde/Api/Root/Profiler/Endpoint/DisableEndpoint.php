<?php
declare(strict_types=1);

namespace Edde\Api\Root\Profiler\Endpoint;

use Edde\Profiler\ProfilerServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class DisableEndpoint extends AbstractMutationEndpoint {
	use ProfilerServiceTrait;

	public function post() {
		$this->profilerService->disable();
	}
}
