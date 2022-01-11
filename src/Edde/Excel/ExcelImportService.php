<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Excel\Dto\HandleDto;
use Edde\File\Dto\FileDto;
use Edde\Import\AbstractImportService;
use Edde\Job\IJob;
use function is_object;

class ExcelImportService extends AbstractImportService implements IExcelImportService {
	use ExcelServiceTrait;

	protected function handle(IJob $job) {
		if (is_object($file = $job->getParams())) {
			$file = $file->file;
		}
		return $this->fileService->useFile($file, function (FileDto $fileDto) use ($job) {
			($progress = $job->getProgress())->check();
			$this->excelService->handle($this->dtoService->fromArray(HandleDto::class, [
				'file' => $fileDto->native,
			]), $progress);
		});
	}
}
