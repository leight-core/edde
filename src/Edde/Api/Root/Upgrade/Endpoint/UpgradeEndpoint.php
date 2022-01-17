<?php
declare(strict_types=1);

namespace Edde\Api\Root\Upgrade\Endpoint;

use Edde\Job\Dto\JobDto;
use Edde\Phinx\UpgradeJobServiceTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

class UpgradeEndpoint extends AbstractMutationEndpoint {
	use UpgradeJobServiceTrait;

	public function post(): JobDto {
		return $this->upgradeJobService->async();
	}
}
