<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

trait JobLogMapperTrait {
	/** @var JobLogMapper */
	protected $jobLogMapper;

	/**
	 * @Inject
	 *
	 * @param JobLogMapper $jobLogMapper
	 */
	public function setJobLogMapper(JobLogMapper $jobLogMapper): void {
		$this->jobLogMapper = $jobLogMapper;
	}
}
