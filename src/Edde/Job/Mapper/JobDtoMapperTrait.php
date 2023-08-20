<?php
declare(strict_types=1);

namespace Edde\Job\Mapper;

/**
 * A little service to map database row to http output.
 */
trait JobDtoMapperTrait {
	/** @var JobDtoMapper */
	protected $jobDtoMapper;

	/**
	 * @Inject
	 *
	 * @param JobDtoMapper $jobDtoMapper
	 */
	public function setJobDtoMapper(JobDtoMapper $jobDtoMapper): void {
		$this->jobDtoMapper = $jobDtoMapper;
	}
}
