<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use DateTime;
use Edde\Doctrine\AbstractRepository;
use Edde\Job\Entity\JobLogEntity;
use Edde\Job\Schema\JobLog\JobLogCreateSchema;

class JobLogRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(JobLogEntity::class);
		$this->orderBy = [
			'stamp' => 'asc',
		];
	}

	public function log(string $jobId, int $level, string $message, $context = null, string $type = null, string $reference = null) {
		return $this->save(
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
