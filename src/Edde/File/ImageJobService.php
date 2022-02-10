<?php
declare(strict_types=1);

namespace Edde\File;

use Edde\Job\AbstractJobService;
use Edde\Job\IJob;

class ImageJobService extends AbstractJobService {
	use FileGcServiceTrait;

	protected function handle(IJob $job) {
		$progress = $job->getProgress();
		$progress->onStart();
		$this->fileGcService->async();
	}
}
