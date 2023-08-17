<?php
declare(strict_types=1);

namespace Edde\Api\Root\Upgrade\Endpoint;

use Edde\Dto\SmartDto;
use Edde\Phinx\UpgradeAsyncServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class UpgradeEndpoint extends AbstractMutationEndpoint {
	use UpgradeAsyncServiceTrait;

	public function post(): SmartDto {
		return $this->upgradeAsyncService->async();
	}
}
