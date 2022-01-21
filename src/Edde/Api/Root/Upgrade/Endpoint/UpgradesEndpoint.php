<?php
declare(strict_types=1);

namespace Edde\Api\Root\Upgrade\Endpoint;

use Edde\Phinx\Dto\UpgradeDto;
use Edde\Phinx\Dto\UpgradeFilterDto;
use Edde\Phinx\Dto\UpgradeOrderByDto;
use Edde\Phinx\Mapper\UpgradeMapperTrait;
use Edde\Phinx\UpgradeManagerTrait;
use Edde\Query\Dto\Query;
use Edde\Query\Dto\QueryResult;
use Edde\Rest\Endpoint\AbstractQueryEndpoint;
use function array_filter;
use function strcmp;

class UpgradesEndpoint extends AbstractQueryEndpoint {
	use UpgradeManagerTrait;
	use UpgradeMapperTrait;

	/**
	 * @param Query<UpgradeOrderByDto, UpgradeFilterDto> $query
	 *
	 * @return QueryResult<UpgradeDto>
	 */
	public function post(Query $query): QueryResult {
		$upgrades = $this->upgradeMapper->map($this->upgradeManager->migrations());
		($query->orderBy->name ?? null) !== null && usort($upgrades, function (UpgradeDto $a, UpgradeDto $b) use ($query) {
			return $query->orderBy->name ? strcmp($a->name, $b->name) : strcmp($b->name, $a->name);
		});
		($query->orderBy->version ?? null) !== null && usort($upgrades, function (UpgradeDto $a, UpgradeDto $b) use ($query) {
			return $query->orderBy->version ? strcmp($a->version, $b->version) : strcmp($b->version, $a->version);
		});
		($query->orderBy->active ?? null) !== null && usort($upgrades, function (UpgradeDto $a, UpgradeDto $b) use ($query) {
			return $query->orderBy->active ? $a->active : $b->active;
		});
		isset($query->filter->active) && $upgrades = array_filter($upgrades, function (UpgradeDto $upgrade) use ($query) {
			return $upgrade->active === $query->filter->active;
		});
		return $this->queryService->toResponse($upgrades);
	}
}
