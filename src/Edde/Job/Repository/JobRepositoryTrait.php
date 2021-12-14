<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

trait JobRepositoryTrait {
	/** @var JobRepository */
	protected $jobRepository;

	/**
	 * @Inject
	 *
	 * @param JobRepository $jobRepository
	 */
	public function setJobRepository(JobRepository $jobRepository): void {
		$this->jobRepository = $jobRepository;
	}
}
