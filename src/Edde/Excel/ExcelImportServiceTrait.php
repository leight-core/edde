<?php
declare(strict_types=1);

namespace Edde\Excel;

trait ExcelImportServiceTrait {
	/** @var IExcelImportService */
	protected $excelImportService;

	/**
	 * @Inject
	 *
	 * @param IExcelImportService $excelImportService
	 */
	public function setExcelImportService(IExcelImportService $excelImportService): void {
		$this->excelImportService = $excelImportService;
	}
}
