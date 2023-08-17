<?php
declare(strict_types=1);

namespace Edde\Excel;

use Edde\Dto\SmartDto;
use Edde\Excel\Dto\HandleDto;
use Edde\File\Dto\FileDto;
use Edde\Import\AbstractImportService;
use Edde\Progress\IProgress;

class ExcelImportService extends AbstractImportService implements IExcelImportService {
	use ExcelServiceTrait;

	protected function handle(SmartDto $job) {
		$file = $job->getSmartDto('request')->getValue('file');
		return $this->fileService->useFile($file, function (FileDto $fileDto) use ($job) {
			/** @var $progress IProgress */
			($progress = $job->getValue('withProgress'))->check();
			$this->excelService->handle($this->dtoService->fromArray(HandleDto::class, [
				'file' => $fileDto->native,
			]), $progress);
		});
	}
}
