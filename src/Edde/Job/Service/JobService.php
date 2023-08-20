<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use DateTime;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Async\IAsyncService;
use Edde\Job\Mapper\JobDtoMapperTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Job\Schema\Job\JobSchema;
use Edde\User\CurrentUserServiceTrait;

class JobService implements IJobService {
	use SmartServiceTrait;
	use JobRepositoryTrait;
	use JobDtoMapperTrait;
	use CurrentUserServiceTrait;

	public function create(IAsyncService $asyncService, ?SmartDto $request): SmartDto {
		return $this->jobDtoMapper->item(
			$this->jobRepository->save(
				$this->smartService->from([
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
				], JobSchema::class)
			)
		);
	}

	public function patch(SmartDto $patch): SmartDto {
		return $this->jobDtoMapper->item(
			$this->jobRepository->patch($patch)
		);
	}

	public function find(string $jobId): SmartDto {
		return $this->jobDtoMapper->item(
			$this->jobRepository->find($jobId)
		);
	}
}
