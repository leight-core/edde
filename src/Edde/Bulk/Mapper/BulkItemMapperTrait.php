<?php
declare(strict_types=1);

namespace Edde\Bulk\Mapper;

trait BulkItemMapperTrait {
	/**
	 * @var BulkItemMapper
	 */
	protected $bulkItemMapper;

	/**
	 * @Inject
	 *
	 * @param BulkItemMapper $bulkItemMapper
	 *
	 * @return void
	 */
	public function setBulkItemMapper(BulkItemMapper $bulkItemMapper): void {
		$this->bulkItemMapper = $bulkItemMapper;
	}
}
