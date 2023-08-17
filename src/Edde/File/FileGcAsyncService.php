<?php
declare(strict_types=1);

namespace Edde\File;

use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Progress\IProgress;

class FileGcAsyncService extends AbstractAsyncService {
	use FileServiceTrait;

	protected function handle(SmartDto $job) {
		/** @var $progress IProgress */
		$progress = $job->getValue('withProgress');
		$progress->onStart();
		$result = $this->fileService->gc(true);
		$progress->onProgress();
		return $result;
	}
}
