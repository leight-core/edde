<?php
declare(strict_types=1);

namespace Edde\Job\Async;

use Edde\Dto\SmartDto;

interface IAsyncService {
	/**
	 * Run the given job; a service must understand job's parameter DTO. Result of this method will
	 * be used as a result of the job.
	 *
	 * @param SmartDto $job
	 *
	 * @return mixed
	 */
	public function job(SmartDto $job);

	/**
	 * Lock this job (service).
	 */
	public function lock(SmartDto $job): void;

	/**
	 * Is this service locked?
	 */
	public function isLocked(SmartDto $job): bool;

	/**
	 * When a job is finished, it should unlock itself.
	 */
	public function unlock(SmartDto $job): void;

	/**
	 * Run the job service async.
	 *
	 * @param mixed|null $params
	 *
	 * @return SmartDto
	 */
	public function async($params = null): SmartDto;
}
