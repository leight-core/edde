<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use DateTime;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Async\IAsyncService;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Job\Schema\Job\Internal\JobCreateSchema;
use Edde\User\CurrentUserServiceTrait;

class JobService implements IJobService {
	use SmartServiceTrait;
	use JobRepositoryTrait;
	use CurrentUserServiceTrait;

	public function create(IAsyncService $asyncService, ?SmartDto $request): SmartDto {
		return $this->jobRepository->create(
			$this->smartService->from(
				[
					'service'       => get_class($asyncService),
					'status'        => 0,
					'total'         => 0,
					'progress'      => 0,
					'successCount'  => 0,
					'errorCount'    => 0,
					'skipCount'     => 0,
					'request'       => $request ? $request->export() : null,
					'requestSchema' => $request ? $request->getSchema()->getName() : null,
					'started'       => new DateTime(),
					'userId'        => $this->currentUserService->requiredId(),
				],
				JobCreateSchema::class
			)
		);
	}

	public function query(SmartDto $request): array {
		return $this->jobRepository->query($request);
	}

	public function update(SmartDto $patch): SmartDto {
		return $this->jobRepository->update($patch);
	}

	public function find(string $jobId): SmartDto {
		return $this->jobRepository->find($jobId);
	}
}
