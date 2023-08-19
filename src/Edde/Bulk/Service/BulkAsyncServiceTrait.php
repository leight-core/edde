<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

trait BulkAsyncServiceTrait {
	/**
	 * @var BulkAsyncService
	 */
	protected $bulkAsyncService;

	/**
	 * @Inject
	 *
	 * @param BulkAsyncService $bulkAsyncService
	 *
	 * @return void
	 */
	public function setBulkAsyncService(BulkAsyncService $bulkAsyncService): void {
		$this->bulkAsyncService = $bulkAsyncService;
	}
}
