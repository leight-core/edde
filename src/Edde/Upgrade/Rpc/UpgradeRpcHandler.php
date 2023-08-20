<?php
declare(strict_types=1);

namespace Edde\Upgrade\Rpc;

use Edde\Dto\SmartDto;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Phinx\UpgradeAsyncServiceTrait;
use Edde\Rpc\AbstractRpcHandler;

class UpgradeRpcHandler extends AbstractRpcHandler {
	use UpgradeAsyncServiceTrait;

	protected $responseSchema = JobSchema::class;
	protected $isMutator = true;

	public function handle(SmartDto $request) {
		return $this->upgradeAsyncService->async();
	}
}
