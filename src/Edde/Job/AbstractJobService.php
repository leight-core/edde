<?php
declare(strict_types=1);

namespace Edde\Job;

use Edde\Job\Dto\JobDto;
use Edde\Job\Repository\JobLockRepositoryTrait;
use Edde\Log\LoggerTrait;
use function microtime;
use function sleep;

abstract class AbstractJobService implements IJobService {
	use JobLockRepositoryTrait;
	use JobExecutorTrait;
	use LoggerTrait;

	public function job(IJob $job) {
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

	public function lock(IJob $job) {
		$this->jobLockRepository->insert([
			'job_id' => $job->getId(),
			'name'   => static::class,
			'stamp'  => microtime(true),
			'active' => true,
		]);
	}

	public function isLocked(IJob $job): bool {
		$lock = $this->jobLockRepository
			->select()
			->where('name', static::class)
			->where('active', true)
			->execute()
			->fetch();
		return $lock->job_id !== $job->getId();
	}

	public function unlock(IJob $job) {
		$this->jobLockRepository
			->table()
			->update(['active' => false])
			->where('job_id', $job->getId())
			->where('name', static::class)
			->execute();
	}

	public function async($params = null): JobDto {
		return $this->jobExecutor->execute($this, $params);
	}

	abstract protected function handle(IJob $job);
}
