<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

trait BulkItemServiceTrait {
	/**
	 * @var BulkItemService
	 */
	protected $bulkItemService;

	/**
	 * @Inject
	 *
	 * @param BulkItemService $bulkItemService
	 *
	 * @return void
	 */
	public function setBulkItemService(BulkItemService $bulkItemService): void {
		$this->bulkItemService = $bulkItemService;
	}
}
