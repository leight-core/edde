<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\DtoServiceTrait;
use Edde\Excel\Dto\Export\ExcelExportDto;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Job\IJob;

class ExcelExportJobService extends AbstractAsyncService {
	use ExcelExportServiceTrait;
	use DtoServiceTrait;

	protected function handle(IJob $job) {
		return $this->excelExportService->export($this->dtoService->fromObject(ExcelExportDto::class, $job->getParams()), $job->getProgress());
	}

	public function isLocked(IJob $job): bool {
		/**
		 * This will enable parallel run of multiple export jobs.
		 */
		return false;
	}
}
