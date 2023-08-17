<?php
declare(strict_types=1);

namespace Edde\Job\Progress;

use DI\DependencyException;
use DI\NotFoundException;
use Edde\Container\ContainerTrait;
use Edde\Progress\IProgress;

class JobProgressFactory {
	use ContainerTrait;

	/**
	 * @param string $jobId
	 *
	 * @return IProgress
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function create(string $jobId): IProgress {
		return $this->container->make(JobProgress::class, ['jobId' => $jobId]);
	}
}
