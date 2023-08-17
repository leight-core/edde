<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use DI\Annotation\Inject;

trait JobServiceTrait {
	/**
	 * @var IJobService
	 */
	protected $jobService;

	/**
	 * @Inject
	 *
	 * @param IJobService $jobService
	 *
	 * @return void
	 */
	public function setJobService(IJobService $jobService): void {
		$this->jobService = $jobService;
	}
}
