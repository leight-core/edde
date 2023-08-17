<?php
declare(strict_types=1);

namespace Edde\Job\Progress;

trait JobProgressFactoryTrait {
	/** @var JobProgressFactory */
	protected $jobProgressFactory;

	/**
	 * @Inject
	 *
	 * @param JobProgressFactory $jobProgressFactory
	 */
	public function setJobProgressFactory(JobProgressFactory $jobProgressFactory): void {
		$this->jobProgressFactory = $jobProgressFactory;
	}
}
