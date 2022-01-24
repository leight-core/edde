<?php
declare(strict_types=1);

namespace Edde\Api\Root\Profiler\Endpoint;

use Edde\Profiler\ProfilerServiceTrait;
use Edde\Rest\Endpoint\AbstractEndpoint;

class IsEnabledEndpoint extends AbstractEndpoint {
	use ProfilerServiceTrait;

	public function get(): bool {
		return $this->profilerService->isEnabled();
	}
}
