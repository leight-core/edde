<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\DtoServiceTrait;
use Edde\Job\AbstractJobService;
use Edde\Job\IJob;
use Edde\Source\Dto\QueriesDto;

class ExcelExportJobService extends AbstractJobService {
	use ExcelExportServiceTrait;
	use DtoServiceTrait;

	protected function handle(IJob $job) {
		$progress = $job->getProgress();
		$progress->onStart();
		[
			$queries,
			$template,
			$name,
		] = $job->getParams();
		return $this->excelExportService->export($this->dtoService->fromArray(QueriesDto::class, $queries), $template, $name)->id;
	}
}
