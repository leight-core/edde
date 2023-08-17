<?php
declare(strict_types=1);

namespace Edde\Excel;

trait ExcelExportAsyncServiceTrait {
	/** @var ExcelExportAsyncService */
	protected $excelExportAsyncService;

	/**
	 * @Inject
	 *
	 * @param ExcelExportAsyncService $excelExportAsyncService
	 */
	public function setExcelExportAsyncService(ExcelExportAsyncService $excelExportAsyncService): void {
		$this->excelExportAsyncService = $excelExportAsyncService;
	}
}
