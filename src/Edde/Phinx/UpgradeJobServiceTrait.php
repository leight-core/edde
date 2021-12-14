<?php
declare(strict_types=1);

namespace Edde\Phinx;

trait UpgradeJobServiceTrait {
	/** @var UpgradeJobService */
	protected $upgradeJobService;

	/**
	 * @Inject
	 *
	 * @param UpgradeJobService $upgradeJobService
	 */
	public function setUpgradeJobService(UpgradeJobService $upgradeJobService): void {
		$this->upgradeJobService = $upgradeJobService;
	}
}
