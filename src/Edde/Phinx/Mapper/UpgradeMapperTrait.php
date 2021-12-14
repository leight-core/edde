<?php
declare(strict_types=1);

namespace Edde\Phinx\Mapper;

trait UpgradeMapperTrait {
	/** @var UpgradeMapper */
	protected $upgradeMapper;

	/**
	 * @Inject
	 *
	 * @param UpgradeMapper $upgradeMapper
	 */
	public function setUpgradeMapper(UpgradeMapper $upgradeMapper): void {
		$this->upgradeMapper = $upgradeMapper;
	}
}
