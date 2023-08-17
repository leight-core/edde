<?php
declare(strict_types=1);

namespace Edde\Phinx;

trait UpgradeAsyncServiceTrait {
	/** @var UpgradeAsyncService */
	protected $upgradeAsyncService;

	/**
	 * @Inject
	 *
	 * @param UpgradeAsyncService $upgradeAsyncService
	 */
	public function setUpgradeAsyncService(UpgradeAsyncService $upgradeAsyncService): void {
		$this->upgradeAsyncService = $upgradeAsyncService;
	}
}
