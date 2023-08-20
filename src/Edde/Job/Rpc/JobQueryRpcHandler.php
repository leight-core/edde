<?php
declare(strict_types=1);

namespace Edde\Job\Rpc;

use Edde\Dto\SmartDto;
use Edde\Job\Service\JobServiceTrait;
use Edde\Rpc\AbstractRpcHandler;

class JobQueryRpcHandler extends AbstractRpcHandler {
	use JobServiceTrait;

	public function handle(SmartDto $request) {
		return $this->jobService->query($request);
	}
}
