<?php
declare(strict_types=1);

namespace Edde\Excel;

trait ExcelExportServiceTrait {
	/** @var IExcelExportService */
	protected $excelExportService;

	/**
	 * @Inject
	 *
	 * @param IExcelExportService $excelExportService
	 */
	public function setExcelExportService(IExcelExportService $excelExportService): void {
		$this->excelExportService = $excelExportService;
	}
}
