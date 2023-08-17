<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Dto\SmartDto;

interface IJobLockService {
	public function lock(SmartDto $job): void;

	public function isLocked(SmartDto $job): bool;

	public function unlock(SmartDto $job): void;
}
