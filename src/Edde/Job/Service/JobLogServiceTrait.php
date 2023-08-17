<?php
declare(strict_types=1);

namespace Edde\Job\Service;

trait JobLogServiceTrait {
	/**
	 * @var IJobLogService
	 */
	protected $jobLogService;

	/**
	 * @Inject
	 *
	 * @param IJobLogService $jobLogService
	 *
	 * @return void
	 */
	public function setJobLogService(IJobLogService $jobLogService): void {
		$this->jobLogService = $jobLogService;
	}
}
