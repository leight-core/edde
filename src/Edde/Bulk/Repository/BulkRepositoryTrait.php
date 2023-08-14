<?php
declare(strict_types=1);

namespace Edde\Bulk\Repository;

use DI\Annotation\Inject;

trait BulkRepositoryTrait {
	/**
	 * @var BulkRepository
	 */
	protected $bulkRepository;

	/**
	 * @Inject
	 *
	 * @param BulkRepository $bulkRepository
	 *
	 * @return void
	 */
	public function setBulkRepository(BulkRepository $bulkRepository): void {
		$this->bulkRepository = $bulkRepository;
	}
}
