<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DI\Annotation\Inject;

trait BulkServiceTrait {
	/**
	 * @var BulkService
	 */
	protected $bulkService;

	/**
	 * @Inject
	 *
	 * @param BulkService $bulkService
	 *
	 * @return void
	 */
	public function setBulkService(BulkService $bulkService): void {
		$this->bulkService = $bulkService;
	}
}
