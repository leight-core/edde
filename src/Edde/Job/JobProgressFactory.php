<?php
declare(strict_types=1);

namespace Edde\Job;

use Edde\Container\ContainerTrait;
use Edde\Progress\IProgress;

class JobProgressFactory {
	use ContainerTrait;

	public function create(string $jobId): IProgress {
		return $this->container->make(JobProgress::class, ['jobId' => $jobId]);
	}
}
