<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use DateTime;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Repository\JobLogRepositoryTrait;
use Edde\Job\Schema\JobLog\JobLogCreateSchema;

class JobLogService implements IJobLogService {
	use SmartServiceTrait;
	use JobLogRepositoryTrait;

	public function log(string $jobId, int $level, string $message, $context = null, string $type = null, string $reference = null): SmartDto {
		return $this->jobLogRepository->save(
			$this->smartService->from(
				[
					'jobId'     => $jobId,
					'level'     => $level,
					'message'   => $message,
					'item'      => $context ? json_encode($context) : null,
					'stamp'     => new DateTime(),
					'reference' => $reference ?? $itemDto->index ?? null,
					'type'      => $type ?? "common",
				],
				JobLogCreateSchema::class
			)
		);
	}
}
