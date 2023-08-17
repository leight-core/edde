<?php
declare(strict_types=1);

namespace Edde\Job\Repository;

use Edde\Doctrine\AbstractRepository;
use Edde\Job\Entity\JobLogEntity;

class JobLogRepository extends AbstractRepository {
	public function __construct() {
		parent::__construct(JobLogEntity::class);
		$this->orderBy = [
			'stamp' => 'asc',
		];
	}

	public function log(string $jobId, int $level, string $message, $context = null, string $type = null, string $reference = null) {
		return $this->insert([
			'job_id'    => $jobId,
			'level'     => $level,
			'message'   => $message,
			'item'      => json_encode($context),
			'stamp'     => microtime(true),
			'reference' => $reference ?? $itemDto->index ?? null,
			'type'      => $type ?? "common",
		]);
	}
}
