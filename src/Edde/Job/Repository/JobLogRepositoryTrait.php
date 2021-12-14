<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

trait JobLogRepositoryTrait {
	/** @var JobLogRepository */
	protected $jobLogRepository;

	/**
	 * @Inject
	 *
	 * @param JobLogRepository $jobLogRepository
	 */
	public function setJobLogRepository(JobLogRepository $jobLogRepository): void {
		$this->jobLogRepository = $jobLogRepository;
	}
}
