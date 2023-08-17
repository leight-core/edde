<?php
declare(strict_types=1);

namespace Edde\File;

use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;

class FileGcAsyncService extends AbstractAsyncService {
	use FileServiceTrait;

	protected function handle(SmartDto $job) {
		$progress = $job->getProgress();
		$progress->onStart();
		$result = $this->fileService->gc(true);
		$progress->onProgress();
		return $result;
	}
}
