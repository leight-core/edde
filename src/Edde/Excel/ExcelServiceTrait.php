<?php
declare(strict_types=1);

namespace Edde\Excel;

trait ExcelServiceTrait {
	/** @var ExcelService */
	protected $excelService;

	/**
	 * @Inject
	 *
	 * @param ExcelService $excelService
	 */
	public function setExcelService(ExcelService $excelService): void {
		$this->excelService = $excelService;
	}
}
