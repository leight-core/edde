<?php
declare(strict_types=1);

namespace Edde\Job;

use DateTime;
use Dibi\Row;
use Edde\Job\Dto\Commit\CommitDto;
use Edde\Job\Dto\Interrupt\InterruptDto;
use Edde\Job\Repository\JobRepositoryTrait;

class JobManager {
	use JobRepositoryTrait;

	public function commit(CommitDto $commitDto): Row {
		return $this->jobRepository->update($commitDto->jobId, [
			'commit' => true,
		]);
	}

	public function interrupt(InterruptDto $interruptDto) {
		return $this->jobRepository->update($interruptDto->jobId, [
			'status' => JobStatus::JOB_INTERRUPTED,
			'done'   => new DateTime(),
		]);
	}
}
