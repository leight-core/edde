<?php
declare(strict_types=1);

namespace Edde\File;

use Edde\Job\AbstractJobService;
use Edde\Job\IJob;

class FileGcService extends AbstractJobService {
	use FileServiceTrait;

	protected function handle(IJob $job) {
		$progress = $job->getProgress();
		$progress->onStart();
		$result = $this->fileService->gc(true);
		$progress->onProgress();
		return $result;
	}
}
