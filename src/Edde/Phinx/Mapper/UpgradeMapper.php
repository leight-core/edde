<?php
declare(strict_types=1);

namespace Edde\Phinx\Mapper;

use Edde\Mapper\AbstractMapper;
use Edde\Phinx\Dto\UpgradeDto;
use Edde\Phinx\UpgradeManagerTrait;
use Phinx\Migration\AbstractMigration;

class UpgradeMapper extends AbstractMapper {
	use UpgradeManagerTrait;

	/**
	 * @param AbstractMigration $item
	 *
	 * @return mixed
	 */
	public function item($item) {
		return $this->dtoService->fromArray(UpgradeDto::class, [
			'version' => $item->getVersion(),
			'name'    => $item->getName(),
			'active'  => $this->upgradeManager->isActive($item),
		]);
	}
}
