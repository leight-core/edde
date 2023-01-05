<?php
declare(strict_types=1);

namespace Edde\Api\Root\Upgrade\Endpoint;

use Edde\Phinx\Dto\UpgradeDto;
use Edde\Phinx\Mapper\UpgradeMapperTrait;
use Edde\Phinx\UpgradeManagerTrait;
use Edde\Query\Dto\Query;
use Edde\Rest\Endpoint\AbstractEndpoint;
use function array_filter;
use function strcmp;

class UpgradesEndpoint extends AbstractEndpoint {
	use UpgradeManagerTrait;
	use UpgradeMapperTrait;

	public function post(Query $query) {
		$upgrades = $this->upgradeMapper->map($this->upgradeManager->migrations());
		isset($query->orderBy->name) && usort($upgrades, function (UpgradeDto $a, UpgradeDto $b) use ($query) {
			return $query->orderBy->name ? strcmp($a->name, $b->name) : strcmp($b->name, $a->name);
		});
		(true || isset($query->orderBy->version)) && usort($upgrades, function (UpgradeDto $a, UpgradeDto $b) use ($query) {
			return $query->orderBy->version ? strcmp($a->version, $b->version) : strcmp($b->version, $a->version);
		});
		isset($query->orderBy->active) && usort($upgrades, function (UpgradeDto $a, UpgradeDto $b) use ($query) {
			return $query->orderBy->active ? $a->active : $b->active;
		});
		isset($query->filter->active) && $upgrades = array_filter($upgrades, function (UpgradeDto $upgrade) use ($query) {
			return $upgrade->active === $query->filter->active;
		});
		return array_slice($upgrades, ($query->page ?? 0) * ($query->size ?? 10), $query->size ?? 10);
	}
}
