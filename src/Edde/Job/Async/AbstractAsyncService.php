<?php
declare(strict_types=1);

namespace Edde\Job\Async;

use DateTime;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Executor\JobExecutorTrait;
use Edde\Job\Schema\JobLock\JobLockQuerySchema;
use Edde\Job\Schema\JobLock\JobLockSchema;
use Edde\Job\Service\JobLockServiceTrait;
use Edde\Log\LoggerTrait;
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
			return $this->handle($job);
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
				JobLockSchema::class
			)
		);
	}

	public function isLocked(SmartDto $job): bool {
		return $this->jobLockService->isLocked(
			$job,
			$this->smartService->from([
				'filter' => [
					'name'   => static::class,
					'active' => true,
				],
				'cursor' => [
					'page' => 0,
					'size' => 1,
				],
			], JobLockQuerySchema::class)
		);
	}

	public function unlock(SmartDto $job): void {
		$this->jobLockService->unlock(
			$this->smartService->from([
				'filter' => [
					'active' => false,
					'jobId'  => $job->getValue('id'),
					'name'   => static::class,
				],
			], JobLockQuerySchema::class)
		);
	}

	public function async(SmartDto $request = null): SmartDto {
		return $this->jobExecutor->execute($this, $request);
	}

	abstract protected function handle(SmartDto $job);
}
