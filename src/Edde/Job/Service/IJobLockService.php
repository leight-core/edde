<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Dto\SmartDto;

interface IJobLockService {
	public function lock(SmartDto $jobLock): void;

	public function isLocked(SmartDto $job, SmartDto $query): bool;

	public function unlock(SmartDto $query): void;
}
