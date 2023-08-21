<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Repository\JobLockRepositoryTrait;

class JobLockService implements IJobLockService {
	use JobLockRepositoryTrait;
	use SmartServiceTrait;

	public function lock(SmartDto $jobLock): void {
		$this->jobLockRepository->create($jobLock);
	}

	public function isLocked(SmartDto $job, SmartDto $query): bool {
		if (!($lock = $this->jobLockRepository->findBy($query))) {
			return false;
		}
		return $lock->getValue('jobId') !== $job->getValue('id');
	}

	public function unlock(SmartDto $query): void {
		$this->jobLockRepository->deleteWith($query);
	}
}
