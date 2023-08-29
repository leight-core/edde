<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\SmartDto;
use Edde\Excel\Dto\Export\ExcelExportDto;
use Edde\Excel\Dto\Export\MetaDto;
use Edde\Progress\IProgress;

interface IExcelExportService {
	/**
	 * Get the export meta file.
	 *
	 * @param string $file
	 *
	 * @return MetaDto
	 */
	public function meta(string $file): MetaDto;

	/**
	 * @param ExcelExportDto $excelExportDto
	 *
	 * @return FileDto
	 */
	public function export(ExcelExportDto $excelExportDto, IProgress $progress = null): SmartDto;
}
