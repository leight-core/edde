<?php
declare(strict_types=1);

namespace Edde\Job;

use Edde\Config\ConfigServiceTrait;
use Edde\Job\Dto\JobDto;
use Edde\Job\Exception\JobException;
use Edde\Job\Mapper\JobMapperTrait;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Log\LoggerTrait;
use Edde\Log\TraceServiceTrait;
use Edde\Php\PhpBinaryServiceTrait;
use Edde\Profiler\ProfilerServiceTrait;
use Edde\Progress\IProgress;
use Edde\User\CurrentUserServiceTrait;
use Symfony\Component\Process\Process;
use function get_class;
use function realpath;
use function sleep;
use function sprintf;
use function vsprintf;
use const BLACKFOX_ROOT;

class CliJobExecutor extends AbstractJobExecutor {
	use LoggerTrait;
	use TraceServiceTrait;
	use CurrentUserServiceTrait;
	use PhpBinaryServiceTrait;
	use ConfigServiceTrait;
	use JobRepositoryTrait;
	use JobLogRepositoryTrait;
	use JobProgressFactoryTrait;
	use JobMapperTrait;
	use ProfilerServiceTrait;

	/**
	 * Configuration of the CLI script executable file (for example cli.php; ideally absolute path).
	 */
	const CONFIG_CLI_PHP = 'cli.php';

	/**
	 * @inheritdoc
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
				realpath($this->configService->system(self::CONFIG_CLI_PHP)),
				'job',
				'--trace=' . $this->traceService->trace(),
				'--user=' . $this->currentUserService->requiredId(),
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
			sleep(1);
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
