<?php
declare(strict_types=1);

namespace Edde\Job\Executor;

use Edde\Config\ConfigServiceTrait;
use Edde\Dto\SmartDto;
use Edde\File\FileServiceTrait;
use Edde\Job\Async\IAsyncService;
use Edde\Job\Exception\JobException;
use Edde\Job\Progress\JobProgressFactoryTrait;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Job\Service\JobServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Log\TraceServiceTrait;
use Edde\Php\PhpBinaryServiceTrait;
use Edde\Profiler\ProfilerServiceTrait;
use Edde\Progress\IProgress;
use Edde\User\CurrentUserServiceTrait;
use Symfony\Component\Process\Process;
use function get_class;
use function realpath;
use function sprintf;
use function vsprintf;

class CliJobExecutor extends AbstractJobExecutor {
    use LoggerTrait;
    use TraceServiceTrait;
    use CurrentUserServiceTrait;
    use PhpBinaryServiceTrait;
    use ConfigServiceTrait;
    use JobServiceTrait;
    use JobLogRepositoryTrait;
    use JobProgressFactoryTrait;
    use ProfilerServiceTrait;
    use FileServiceTrait;

    /**
     * Configuration of the CLI script executable file (for example cli.php; ideally absolute path).
     */
    const CONFIG_CLI_PHP = 'cli.php';

    /**
     * @inheritdoc
     */
    public function execute(IAsyncService $asyncService, $params = null, string $reference = null): SmartDto {
        return $this->profilerService->profile(static::class, function () use ($asyncService, $params, $reference) {
            $this->logger->info(sprintf('Executing background job for [%s] in [%s].', get_class($asyncService), static::class), ['tags' => ['job']]);
            $job = $this->createJob($asyncService, $params, $reference);
            /** @var $progress IProgress */
            $progress = $job->getValue('withProgress');
            $progress->log(IProgress::LOG_INFO, sprintf('New job [%s].', $job->getValue('id')));
            $this->logger->info(sprintf('New job [%s].', $job->getValue('id')), ['tags' => ['job']]);
            $php = $this->configService->get('php-cli') ?? $this->phpBinaryService->find();
            $progress->log(IProgress::LOG_INFO, sprintf('PHP executable [%s].', $php));
            $this->logger->info(sprintf('PHP executable [%s].', $php), ['tags' => ['job']]);
            $process = new Process([
                $php,
                realpath($this->configService->system(self::CONFIG_CLI_PHP)),
                'job',
                '--trace=' . $this->traceService->trace(),
                '--user=' . $this->currentUserService->requiredId(),
                $job->getValue('id'),
            ], null, $_SERVER, null, null);
            $process->setOptions(['create_new_console' => true]);
            $process->disableOutput();
            $process->start();
            $progress->log(IProgress::LOG_INFO, vsprintf('Job (probably) running [%s] [pid: %d]. Executor finished [%s].', [
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
                $progress->onFailure($throwable = new JobException(sprintf('Job is not running; check the PHP binary (%s).', $php)));
                throw $throwable;
            }
            $this->logger->info(sprintf('Executed [%s] in [%s].', get_class($asyncService), static::class), ['tags' => ['job']]);
            /**
             * Refresh job as it could get some changes during start (like job-log).
             */
            return $this->jobService->find($job->getValue('id'));
        });
    }
}
