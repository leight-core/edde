<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\DtoServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Progress\IProgress;

class ExcelExportAsyncService extends AbstractAsyncService {
	use ExcelExportServiceTrait;
	use DtoServiceTrait;

	protected function handle(SmartDto $job, IProgress $progress, ?SmartDto $request) {
		return $this->excelExportService->export(
			$request,
			$progress
		);
	}

	public function isLocked(SmartDto $job): bool {
		/**
		 * This will enable parallel run of multiple export jobs.
		 */
		return false;
	}
}
