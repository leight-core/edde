<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Job\Entity\JobLogEntity;

interface IJobLogService {
	public function log(string $jobId, int $level, string $message, $context = null, string $type = null, string $reference = null): JobLogEntity;
}
