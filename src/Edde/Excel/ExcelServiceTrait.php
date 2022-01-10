<?php
declare(strict_types=1);

namespace Edde\Excel;

trait ExcelServiceTrait {
	/** @var IExcelService */
	protected $excelService;

	/**
	 * @Inject
	 *
	 * @param IExcelService $excelService
	 */
	public function setExcelService(IExcelService $excelService): void {
		$this->excelService = $excelService;
	}
}
