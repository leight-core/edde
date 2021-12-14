<?php
declare(strict_types=1);

namespace Edde\Job;

trait JobManagerTrait {
	/** @var JobManager */
	protected $jobManager;

	/**
	 * @Inject
	 *
	 * @param JobManager $jobManager
	 */
	public function setJobManager(JobManager $jobManager): void {
		$this->jobManager = $jobManager;
	}
}
