<?php
declare(strict_types=1);

namespace Edde\Job\Async;

use DateTime;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Exception\JobInterruptedException;
use Edde\Job\Executor\JobExecutorTrait;
use Edde\Job\Schema\JobLock\Internal\JobLockCreateSchema;
use Edde\Job\Schema\JobLock\Query\JobLockQuerySchema;
use Edde\Job\Service\JobLockServiceTrait;
use Edde\Log\LoggerTrait;
use Edde\Php\Exception\MemoryLimitException;
use Edde\Progress\IProgress;
use Throwable;
use function sleep;

abstract class AbstractAsyncService implements IAsyncService {
    use SmartServiceTrait;
    use JobLockServiceTrait;
    use JobExecutorTrait;
    use LoggerTrait;

    public function job(SmartDto $job) {
        /**
         * Lock the job; next lock will be examined and when Job matches,
         * it will run.
         */
        $this->lock($job);
        try {
            while ($this->isLocked($job)) {
                /**
                 * Sleep should do idle CPU cycles, thus eating no resources when waiting.
                 */
                sleep(3);
            }
            return $this->handle(
                $job,
                $job->getValue('withProgress'),
                $job->getValue('withRequest')
            );
        } catch (Throwable $exception) {
            $this->logger->error($exception);
        } finally {
            $this->unlock($job);
        }
    }

    public function lock(SmartDto $job): void {
        $this->jobLockService->lock(
            $this->smartService->from(
                [
                    'jobId'  => $job->getValue('id'),
                    'name'   => static::class,
                    'stamp'  => new DateTime(),
                    'active' => true,
                ],
                JobLockCreateSchema::class
            )
        );
    }

    public function isLocked(SmartDto $job): bool {
        return $this->jobLockService->isLocked(
            $job,
            $this->smartService->from(
                [
                    'filter' => [
                        'name'   => static::class,
                        'active' => true,
                    ],
                    'cursor' => [
                        'page' => 0,
                        'size' => 1,
                    ],
                ],
                JobLockQuerySchema::class
            )
        );
    }

    public function unlock(SmartDto $job): void {
        $this->jobLockService->unlock(
            $this->smartService->from([
                'filter' => [
                    'active' => true,
                    'jobId'  => $job->getValue('id'),
                    'name'   => static::class,
                ],
            ], JobLockQuerySchema::class)
        );
    }

    public function async(SmartDto $request = null, string $reference = null): SmartDto {
        return $this->jobExecutor->execute($this, $request, $reference);
    }

    /**
     * @param SmartDto      $job
     * @param IProgress     $progress
     * @param SmartDto|null $request
     *
     * @return mixed
     * @throws MemoryLimitException
     * @throws JobInterruptedException
     */
    abstract protected function handle(SmartDto $job, IProgress $progress, ?SmartDto $request);
}
