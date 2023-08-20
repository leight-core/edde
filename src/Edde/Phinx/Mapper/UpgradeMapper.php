<?php
declare(strict_types=1);

namespace Edde\Phinx\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Phinx\UpgradeManagerTrait;
use Edde\Upgrade\Schema\UpgradeSchema;
use Phinx\Migration\AbstractMigration;

class UpgradeMapper extends AbstractMapper {
	use UpgradeManagerTrait;

	/**
	 * @param AbstractMigration $item
	 * @param null              $params
	 *
	 * @return mixed
	 */
	public function item($item, $params = null) {
		return $this->smartService->from([
			'id'      => $item->getVersion(),
			'version' => $item->getVersion(),
			'name'    => $item->getName(),
			'active'  => $this->upgradeManager->isActive($item),
		], UpgradeSchema::class);
	}
}
