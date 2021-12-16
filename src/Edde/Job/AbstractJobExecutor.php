<?php
declare(strict_types=1);

namespace Edde\Job;

use Dibi\Exception;
use Edde\Container\ContainerTrait;
use Edde\Job\Dto\JobDto;
use Edde\Job\Exception\JobInterruptedException;
use Edde\Job\Mapper\JobMapperTrait;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Log\LoggerTrait;
use Edde\Progress\IProgress;
use Edde\Translation\LanguageServiceTrait;
use Edde\User\CurrentUserTrait;
use Nette\Utils\Json;
use Throwable;

abstract class AbstractJobExecutor implements IJobExecutor {
	use JobRepositoryTrait;
	use JobLogRepositoryTrait;
	use ContainerTrait;
	use CurrentUserTrait;
	use LoggerTrait;
	use JobProgressFactoryTrait;
	use JobMapperTrait;
	use LanguageServiceTrait;

	protected function createJob(IJobService $jobService, $params = null): JobDto {
		return $this->jobMapper->item($this->jobRepository->create($jobService, $this->currentUser->optionalId(), $params));
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
		$job = $this->jobRepository->find($jobId);
		$this->logger->info(sprintf('Executing job service [%s], user id [%s].', $job->service, $job->user_id), ['tags' => ['job']]);
		$progress = $this->jobProgressFactory->create($jobId);
		$progress->log(IProgress::LOG_INFO, sprintf('Executing job service [%s], user id [%s], language [%s].', $job->service, $job->user_id, $this->languageService->forCurrentUser()));
		try {
			$result = call_user_func([
				$this->container->get($job->service),
				'job',
			], new Job($job->id, Json::decode($job->params), $progress, $job->user_id ? (string)$job->user_id : null));
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
