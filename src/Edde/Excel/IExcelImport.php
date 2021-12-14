<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Import\IImportService;
use Edde\Progress\IProgress;

interface IExcelImport extends IImportService {
	public function sheet(string $file, string $sheet = null, IProgress $progress = null);
}
