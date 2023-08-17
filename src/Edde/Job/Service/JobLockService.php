<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Doctrine\EntityManagerTrait;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Repository\JobLockRepositoryTrait;

class JobLockService implements IJobLockService {
	use JobLockRepositoryTrait;
	use SmartServiceTrait;
	use EntityManagerTrait;

	public function lock(SmartDto $jobLock): void {
		$this->jobLockRepository->save($jobLock);
		$this->entityManager->flush();
	}

	public function isLocked(SmartDto $job, SmartDto $query): bool {
		return $this->jobLockRepository->findByOrThrow($query)->jobId !== $job->getValue('id');
	}

	public function unlock(SmartDto $query): void {
		$this->jobLockRepository->deleteWith($query);
		$this->entityManager->flush();
	}
}
