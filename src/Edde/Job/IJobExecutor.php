<?php
declare(strict_types=1);

namespace Edde\Job;

use Dibi\Exception;
use Edde\Job\Dto\JobDto;

interface IJobExecutor {
	/**
	 * Asynchronously execute the given job request.
	 *
	 * @param IJobService $jobService object required to enforce PHP's checks and also ensure the service exists; it's not actually called, just used as an object.
	 * @param mixed|null  $params     optional parameter DTO for the Job service being called
	 *
	 * @return JobDto
	 */
	public function execute(IJobService $jobService, $params = null): JobDto;

	/**
	 * Actually run the long running job (that means - do not call this method in the common code).
	 *
	 * @param string $jobId
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 */
	public function run(string $jobId);
}
