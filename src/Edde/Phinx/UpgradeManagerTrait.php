<?php
declare(strict_types=1);

namespace Edde\Phinx;

trait UpgradeManagerTrait {
	/** @var UpgradeManager */
	protected $upgradeManager;

	/**
	 * @Inject
	 *
	 * @param UpgradeManager $upgradeManager
	 */
	public function setUpgradeManager(UpgradeManager $upgradeManager): void {
		$this->upgradeManager = $upgradeManager;
	}
}
