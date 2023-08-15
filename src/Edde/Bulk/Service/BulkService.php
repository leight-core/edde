<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Mapper\BulkDtoMapperTrait;
use Edde\Bulk\Repository\BulkRepositoryTrait;
use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\User\CurrentUserServiceTrait;

class BulkService {
	use CurrentUserServiceTrait;
	use BulkRepositoryTrait;
	use BulkDtoMapperTrait;
	use SmartServiceTrait;

	public function create(SmartDto $request): SmartDto {
		return $this->bulkDtoMapper->item(
			$this->bulkRepository->save(
				$this->smartService->cloneTo($request, BulkSchema::class, [
					'created' => new DateTime(),
					'status' => 0,
					'commit' => false,
					'userId'  => $this->currentUserService->requiredId(),
				])
			)
		);
	}

	public function fetch(SmartDto $request): SmartDto {
		return $this->bulkDtoMapper->item($this->bulkRepository->find($request->getValue('id')));
	}

	public function delete(SmartDto $request): SmartDto {
		return $this->bulkDtoMapper->item($this->bulkRepository->deleteBy($request));
	}

	public function commit(SmartDto $request) {
		$this->bulkRepository->patch($this->smartService->cloneTo($request, BulkSchema::class, [
			'commit' => true,
		]));
	}

	public function query(SmartDto $request): array {
		return $this->bulkDtoMapper->map($this->bulkRepository->withQuery('b', $request));
	}
}
