<?php
declare(strict_types=1);

namespace Edde\Job;

use Edde\Job\Dto\JobDto;

interface IJobService {
	/**
	 * Run the given job; a service must understand job's parameter DTO. Result of this method will
	 * be used as a result of the job.
	 *
	 * @param IJob $job
	 *
	 * @return mixed
	 */
	public function job(IJob $job);

	/**
	 * Lock this job (service).
	 */
	public function lock(IJob $job);

	/**
	 * Is this service locked?
	 */
	public function isLocked(IJob $job): bool;

	/**
	 * When a job is finished, it should unlock itself.
	 */
	public function unlock(IJob $job);

	/**
	 * Run the job service async.
	 *
	 * @param mixed|null $params
	 *
	 * @return JobDto
	 */
	public function async($params = null): JobDto;
}
