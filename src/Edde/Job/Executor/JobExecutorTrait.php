<?php
declare(strict_types=1);

namespace Edde\Job\Executor;

/**
 * Magical service used to execute jobs at background (workers).
 */
trait JobExecutorTrait {
	/** @var IJobExecutor */
	protected $jobExecutor;

	/**
	 * @Inject
	 *
	 * @param IJobExecutor $jobExecutor
	 */
	public function setJobExecutor(IJobExecutor $jobExecutor): void {
		$this->jobExecutor = $jobExecutor;
	}
}
