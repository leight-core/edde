<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

trait ExportMapperTrait {
	/**
	 * @var ExportMapper
	 */
	protected $exportMapper;

	/**
	 * @Inject
	 *
	 * @param ExportMapper $exportMapper
	 *
	 * @return void
	 */
	public function setExportMapper(ExportMapper $exportMapper): void {
		$this->exportMapper = $exportMapper;
	}
}
