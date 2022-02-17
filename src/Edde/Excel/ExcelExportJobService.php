<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\DtoServiceTrait;
use Edde\Excel\Dto\Export\ExcelExportDto;
use Edde\Job\AbstractJobService;
use Edde\Job\IJob;

class ExcelExportJobService extends AbstractJobService {
	use ExcelExportServiceTrait;
	use DtoServiceTrait;

	protected function handle(IJob $job) {
		return $this->excelExportService->export($this->dtoService->fromObject(ExcelExportDto::class, $job->getParams()), $job->getProgress());
	}
}
