<?php
declare(strict_types=1);

namespace Edde\Bulk\Mapper;

trait BulkItemDtoMapperTrait {
	/**
	 * @var BulkItemDtoMapper
	 */
	protected $bulkItemDtoMapper;

	/**
	 * @Inject
	 *
	 * @param BulkItemDtoMapper $bulkItemDtoMapper
	 *
	 * @return void
	 */
	public function setBulkItemDtoMapper(BulkItemDtoMapper $bulkItemDtoMapper): void {
		$this->bulkItemDtoMapper = $bulkItemDtoMapper;
	}
}
