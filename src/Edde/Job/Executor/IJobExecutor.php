<?php
declare(strict_types=1);

namespace Edde\Job\Executor;

use Dibi\Exception;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Job\Async\IAsyncService;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\User\Exception\UserNotSelectedException;
use ReflectionException;

interface IJobExecutor {
	/**
	 * Asynchronously execute the given job request.
	 *
	 * @param IAsyncService $asyncService object required to enforce PHP's checks and also ensure the service exists; it's not actually called, just used as an object.
	 * @param mixed|null    $params       optional parameter DTO for the Job service being called
	 *
	 * @return SmartDto
	 * @throws SmartDtoException
	 * @throws ItemException
	 * @throws SkipException
	 * @throws UserNotSelectedException
	 * @throws ReflectionException
	 */
	public function execute(IAsyncService $asyncService, $params = null): SmartDto;

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
