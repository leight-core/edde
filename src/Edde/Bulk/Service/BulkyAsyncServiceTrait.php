<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

trait BulkyAsyncServiceTrait {
	/**
	 * @var BulkyAsyncService
	 */
	protected $bulkyAsyncService;

	/**
	 * @Inject
	 *
	 * @param BulkyAsyncService $bulkyAsyncService
	 *
	 * @return void
	 */
	public function setBulkyAsyncService(BulkyAsyncService $bulkyAsyncService): void {
		$this->bulkyAsyncService = $bulkyAsyncService;
	}
}
