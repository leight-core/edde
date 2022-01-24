<?php
declare(strict_types=1);

namespace Edde\Api\Root\Job\Endpoint;

use Dibi\Exception;
use Edde\Job\JobExecutorTrait;
use Edde\Rest\Endpoint\AbstractEndpoint;
use Edde\Rest\Exception\RestException;

/**
 * Be careful about this endpoint as it will block the thread until it's job is done.
 *
 * At common all jobs should take some kind of short to mid range time (like minutes or at most tens of minutes) as
 * it takes server thread (thus block it) which means a lot of jobs could overflow the server.
 *
 * If there will be need for a lot of long running jobs, it's necessary to create kind of Job Manager which will control
 * how many jobs are running.
 *
 * Also a job could be rejected until somebody asks for it's status (thus executing the given job, because the client is
 * still interested in it's status).
 *
 * @description Executes the selected job. Be careful, this may take quite long, so use it when you know what are you doing.
 * @query       jobId
 */
class ExecuteEndpoint extends AbstractEndpoint {
	use JobExecutorTrait;

	/**
	 * @return mixed
	 *
	 * @throws Exception
	 * @throws RestException
	 */
	public function get() {
		/**
		 * In general, nobody should care about the result as this could take minutes or tens of minutes before completion.
		 */
		return $this->jobExecutor->run($this->param('jobId'));
	}
}
