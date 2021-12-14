<?php
declare(strict_types=1);

namespace Edde\Job;

use ClanCats\Hydrahon\Query\Sql\Exception;
use Edde\Config\ConfigServiceTrait;
use Edde\Job\Dto\JobDto;
use Edde\Job\Exception\JobException;
use Edde\Job\Mapper\JobMapperTrait;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Log\LoggerTrait;
use Edde\Log\TraceServiceTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\Php\PhpBinaryServiceTrait;
use Edde\Profiler\ProfilerServiceTrait;
use Edde\Progress\IProgress;
use Edde\Repository\Exception\RepositoryException;
use Marsh\User\CurrentUserTrait;
use Marsh\User\Exception\UserNotSelectedException;
use Symfony\Component\Process\Process;
use function get_class;
use function realpath;
use function sprintf;
use function vsprintf;
use const BLACKFOX_ROOT;

class CliJobExecutor extends AbstractJobExecutor {
	use LoggerTrait;
	use TraceServiceTrait;
	use CurrentUserTrait;
	use PhpBinaryServiceTrait;
	use ConfigServiceTrait;
	use JobRepositoryTrait;
	use JobLogRepositoryTrait;
	use JobProgressFactoryTrait;
	use JobMapperTrait;
	use ProfilerServiceTrait;

	/**
	 * @param IJobService $jobService
	 * @param null        $params
	 *
	 * @return JobDto
	 *
	 * @throws ItemException
	 * @throws JobException
	 * @throws RepositoryException
	 * @throws SkipException
	 * @throws UserNotSelectedException
	 * @throws Exception
	 */
	public function execute(IJobService $jobService, $params = null): JobDto {
		return $this->profilerService->profile(static::class, function () use ($jobService, $params) {
			$this->logger->info(sprintf('Executing background job for [%s] in [%s].', get_class($jobService), static::class), ['tags' => ['job']]);
			$job = $this->createJob($jobService, $params);
			$jobProgress = $this->jobProgressFactory->create($job->id);
			$jobProgress->log(IProgress::LOG_INFO, sprintf('New job [%s].', $job->id));
			$this->logger->info(sprintf('New job [%s].', $job->id), ['tags' => ['job']]);
			$php = $this->configService->get('php-cli') ?? $this->phpBinaryService->find();
			$jobProgress->log(IProgress::LOG_INFO, sprintf('PHP executable [%s].', $php));
			$this->logger->info(sprintf('PHP executable [%s].', $php), ['tags' => ['job']]);
			$process = new Process([
				$php,
				realpath(BLACKFOX_ROOT . '/cli.php'),
				'job',
				'--trace=' . $this->traceService->trace(),
				'--user=' . $this->currentUser->requiredId(),
				$job->id,
			], null, null, null, null);
			$process->setOptions(['create_new_console' => true]);
			$process->disableOutput();
			$process->start();
			$jobProgress->log(IProgress::LOG_INFO, vsprintf('Job (probably) running [%s] [pid: %d]. Executor finished [%s].', [
				$process->isRunning() ? 'yes' : 'no',
				$process->getPid(),
				$process->getCommandLine(),
			]));
			$this->logger->info(
				vsprintf('Job (probably) running [%s] [pid: %d]. Executor finished [%s].', [
					$process->isRunning() ? 'yes' : 'no',
					$process->getPid(),
					$process->getCommandLine(),
				]),
				['tags' => ['job']]
			);
			if (!$process->isRunning()) {
				$jobProgress->onFailure($throwable = new JobException(sprintf('Job is not running; check the PHP binary (%s).', $php)));
				throw $throwable;
			}
			/**
			 * Refresh job as it could get some changes during start (like job-log).
			 */
			return $this->jobMapper->item($this->jobRepository->find($job->id));
		});
	}
}