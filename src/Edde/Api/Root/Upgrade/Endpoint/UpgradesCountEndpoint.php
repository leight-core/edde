<?php
declare(strict_types=1);

namespace Edde\Api\Root\Upgrade\Endpoint;

use Edde\Phinx\Dto\UpgradeDto;
use Edde\Phinx\Mapper\UpgradeMapperTrait;
use Edde\Phinx\UpgradeManagerTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;
use function array_filter;

/**
 * @alterLink /upgrades/count
 */
class UpgradesCountEndpoint extends AbstractEndpoint {
	use UpgradeManagerTrait;
	use UpgradeMapperTrait;

	public function post(Query $query): int {
		$upgrades = $this->upgradeMapper->map($this->upgradeManager->migrations());
		isset($query->filter->active) && $upgrades = array_filter($upgrades, function (UpgradeDto $upgrade) use ($query) {
			return $upgrade->active === $query->filter->active;
		});
		return count($upgrades);
	}
}
