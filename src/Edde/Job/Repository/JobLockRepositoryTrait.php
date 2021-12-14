<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

trait JobLockRepositoryTrait {
	/** @var JobLockRepository */
	protected $jobLockRepository;

	/**
	 * @Inject
	 *
	 * @param JobLockRepository $jobLockRepository
	 */
	public function setJobLockRepository(JobLockRepository $jobLockRepository): void {
		$this->jobLockRepository = $jobLockRepository;
	}
}
