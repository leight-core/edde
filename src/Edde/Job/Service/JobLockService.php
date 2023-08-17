<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Dto\SmartDto;

class JobLockService implements IJobLockService {
	public function lock(SmartDto $job): void {
	}

	public function isLocked(SmartDto $job): bool {
		$lock = $this->jobLockRepository
			->select()
			->where('name', static::class)
			->where('active', true)
			->execute()
			->fetch();
		return $lock->job_id !== $job->getId();
	}

	public function unlock(SmartDto $job): void {
		$this->jobLockRepository
			->table()
			->update(['active' => false])
			->where('job_id', $job->getId())
			->where('name', static::class)
			->execute();
	}
}
