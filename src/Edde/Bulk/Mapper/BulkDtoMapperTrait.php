<?php
declare(strict_types=1);

namespace Edde\Bulk\Mapper;

use DI\Annotation\Inject;

trait BulkDtoMapperTrait {
	/**
	 * @var BulkDtoMapper
	 */
	protected $bulkDtoMapper;

	/**
	 * @Inject
	 *
	 * @param BulkDtoMapper $bulkDtoMapper
	 *
	 * @return void
	 */
	public function setBulkDtoMapper(BulkDtoMapper $bulkDtoMapper): void {
		$this->bulkDtoMapper = $bulkDtoMapper;
	}
}
