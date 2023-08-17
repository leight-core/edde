<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use DI\Annotation\Inject;

trait JobLockServiceTrait {
	/**
	 * @var IJobLockService
	 */
	protected $jobLockService;

	/**
	 * @Inject
	 *
	 * @param IJobLockService $jobLockService
	 *
	 * @return void
	 */
	public function setJobLockService(IJobLockService $jobLockService): void {
		$this->jobLockService = $jobLockService;
	}
}
