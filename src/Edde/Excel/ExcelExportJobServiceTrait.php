<?php
declare(strict_types=1);

namespace Edde\Excel;

trait ExcelExportJobServiceTrait {
	/** @var ExcelExportJobService */
	protected $excelExportJobService;

	/**
	 * @Inject
	 *
	 * @param ExcelExportJobService $excelExportJobService
	 */
	public function setExcelExportJobService(ExcelExportJobService $excelExportJobService): void {
		$this->excelExportJobService = $excelExportJobService;
	}
}
