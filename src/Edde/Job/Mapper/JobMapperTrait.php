<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

/**
 * A little service to map database row to http output.
 */
trait JobMapperTrait {
	/** @var JobMapper */
	protected $jobMapper;

	/**
	 * @Inject
	 *
	 * @param JobMapper $jobMapper
	 */
	public function setJobMapper(JobMapper $jobMapper): void {
		$this->jobMapper = $jobMapper;
	}
}
