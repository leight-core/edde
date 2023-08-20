<?php
declare(strict_types=1);

namespace Edde\Bulk\Job;

trait BulkImportAsyncServiceTrait {
	/**
	 * @var BulkImportAsyncService
	 */
	protected $bulkImportAsyncService;

	/**
	 * @Inject
	 *
	 * @param BulkImportAsyncService $bulkImportAsyncService
	 *
	 * @return void
	 */
	public function setBulkImportAsyncService(BulkImportAsyncService $bulkImportAsyncService): void {
		$this->bulkImportAsyncService = $bulkImportAsyncService;
	}
}
