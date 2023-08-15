<?php
declare(strict_types=1);

namespace Edde\Bulk\Repository;

trait BulkItemRepositoryTrait {
	/**
	 * @var BulkItemRepository
	 */
	protected $bulkItemRepository;

	/**
	 * @Inject
	 *
	 * @param BulkItemRepository $bulkItemRepository
	 *
	 * @return void
	 */
	public function setBulkItemRepository(BulkItemRepository $bulkItemRepository): void {
		$this->bulkItemRepository = $bulkItemRepository;
	}
}
