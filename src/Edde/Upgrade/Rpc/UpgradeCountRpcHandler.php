<?php
declare(strict_types=1);

namespace Edde\Upgrade\Rpc;

use Edde\Dto\SmartDto;
use Edde\Phinx\Mapper\UpgradeMapperTrait;
use Edde\Phinx\UpgradeManagerTrait;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Upgrade\Schema\UpgradeQuerySchema;

class UpgradeCountRpcHandler extends AbstractRpcHandler {
    use UpgradeManagerTrait;
    use UpgradeMapperTrait;

    protected $requestSchema = UpgradeQuerySchema::class;

    public function handle(SmartDto $request) {
        $filter = $request->getSmartDto('filter', true);
        $upgrades = $this->upgradeMapper->map($this->upgradeManager->migrations());
        if ($filter->knownWithValue('active')) {
            $active = $filter->getValue('active');
            $upgrades = array_filter($upgrades, function (SmartDto $upgrade) use ($active) {
                return $upgrade->getValue('active') === $active;
            });
        }
        usort($upgrades, function (SmartDto $a, SmartDto $b) {
            return strcmp($a->getValue('version'), $b->getValue('version'));
        });
        return count($upgrades);
    }
}
