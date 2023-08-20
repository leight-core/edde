<?php
declare(strict_types=1);

namespace Edde\Upgrade\Rpc;

use Edde\Dto\SmartDto;
use Edde\Phinx\Mapper\UpgradeMapperTrait;
use Edde\Phinx\UpgradeManagerTrait;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Upgrade\Schema\UpgradeFilterSchema;
use Edde\Upgrade\Schema\UpgradeOrderBySchema;
use Edde\Upgrade\Schema\UpgradeQuerySchema;
use Edde\Upgrade\Schema\UpgradeSchema;

class UpgradeQueryRpcHandler extends AbstractRpcHandler {
	use UpgradeManagerTrait;
	use UpgradeMapperTrait;

	protected $requestSchema = UpgradeQuerySchema::class;
	protected $responseSchema = UpgradeSchema::class;
	protected $responseSchemaArray = true;
	protected $orderBySchema = UpgradeOrderBySchema::class;
	protected $filterSchema = UpgradeFilterSchema::class;
	protected $isQuery = true;

	public function handle(SmartDto $request) {
		$filter = $request->getSmartDto('filter', true);
		$upgrades = $this->upgradeMapper->map($this->upgradeManager->migrations());
		usort($upgrades, function (SmartDto $a, SmartDto $b) {
			return strcmp($a->getValue('version'), $b->getValue('version'));
		});
		$filter->knownWithValue('active') && $upgrades = array_filter($upgrades, function (SmartDto $upgrade) use ($filter) {
			return $upgrade->active === $filter->getValue('active');
		});
		return array_slice($upgrades, ($query->page ?? 0) * ($query->size ?? 10), $query->size ?? 10);
	}
}
