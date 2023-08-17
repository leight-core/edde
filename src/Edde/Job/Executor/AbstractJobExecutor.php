<?php
declare(strict_types=1);

namespace Edde\Job\Executor;

use Dibi\Exception;
use Edde\Container\ContainerTrait;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Async\IAsyncService;
use Edde\Job\Exception\JobException;
use Edde\Job\Exception\JobInterruptedException;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Job\Service\JobServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Progress\IProgress;
use Edde\Translation\LanguageServiceTrait;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Exception\UserNotSelectedException;
use ReflectionException;
use Throwable;

abstract class AbstractJobExecutor implements IJobExecutor {
	use JobRepositoryTrait;
	use JobLogRepositoryTrait;
	use ContainerTrait;
	use CurrentUserServiceTrait;
	use SmartServiceTrait;
	use LoggerTrait;
	use JobServiceTrait;
	use LanguageServiceTrait;

	/**
	 * @param IAsyncService $asyncService
	 * @param SmartDto|null $request
	 *
	 * @return SmartDto
	 * @throws SmartDtoException
	 * @throws ItemException
	 * @throws SkipException
	 * @throws UserNotSelectedException
	 * @throws ReflectionException
	 */
	protected function createJob(IAsyncService $asyncService, SmartDto $request = null): SmartDto {
		return $this->jobService->create($asyncService, $request);
	}

	/**
	 * Actually run the long running job (that means - do not call this method in the common code).
	 *
	 * @param string $jobId
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 * @throws Throwable
	 */
	public function run(string $jobId) {
		$this->logger->info(sprintf('Running job [%s]', $jobId), ['tags' => ['job']]);
		$job = $this->jobService->find($jobId);
		$this->logger->info(sprintf('Executing job service [%s], user id [%s].', $job->getValue('service'), $job->getValue('userId')), ['tags' => ['job']]);
		/** @var $progress IProgress */
		$progress = $job->getValue('withProgress');
		$progress->log(IProgress::LOG_INFO, sprintf('Executing job service [%s], user id [%s], language [%s].', $job->getValue('service'), $job->getValue('userId'), $this->languageService->forCurrentUser()));
		try {
			if (!($service = $this->container->get($job->getValue('service'))) instanceof IAsyncService) {
				throw new JobException(sprintf('Requested service [%s] is not instance of [%s].', get_class($service), IAsyncService::class));
			}
			$result = call_user_func(
				[
					$service,
					'job',
				],
				$job
			);
			$progress->log(IProgress::LOG_INFO, 'Job successfully finished.');
			$this->logger->info('Job successfully finished, setting job done.', ['tags' => ['job']]);
			$progress->onDone($result);
			return $result;
		} catch (JobInterruptedException $exception) {
			$progress->log(IProgress::LOG_INFO, sprintf('Job [%s] has been interrupted.', $jobId));
			$this->logger->notice(sprintf('Job [%s] has been interrupted.', $jobId), ['tags' => ['job']]);
		} catch (Throwable $throwable) {
			$progress->onFailure($throwable);
			$this->logger->error($throwable, ['tags' => ['job']]);
			throw $throwable;
		} finally {
			$this->logger->info('Finished.', ['tags' => ['job']]);
		}
		return null;
	}
}
