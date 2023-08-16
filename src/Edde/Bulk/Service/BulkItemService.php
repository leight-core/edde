<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Mapper\BulkItemDtoMapperTrait;
use Edde\Bulk\Repository\BulkItemRepositoryTrait;
use Edde\Bulk\Schema\BulkItem\Internal\BulkItemUpsertRequestSchema;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\User\CurrentUserServiceTrait;

class BulkItemService {
	use SmartServiceTrait;
	use BulkItemRepositoryTrait;
	use BulkItemDtoMapperTrait;
	use CurrentUserServiceTrait;

	public function fetch(SmartDto $request): SmartDto {
		return $this->bulkItemDtoMapper->item(
			$this->bulkItemRepository->find($request->getValue('id'))
		);
	}

	public function query(SmartDto $request): array {
		return $this->bulkItemDtoMapper->map(
			$this->bulkItemRepository->withQuery('b', $request)
		);
	}

	public function delete(SmartDto $request): SmartDto {
		return $this->bulkItemDtoMapper->item(
			$this->bulkItemRepository->deleteBy($request)
		);
	}

	public function upsert(SmartDto $request): SmartDto {
		$create = [
			'status'  => 0,
			'created' => new DateTime(),
			'userId'  => $this->currentUserService->requiredId(),
		];
		return $this->bulkItemDtoMapper->item(
			$this->bulkItemRepository->upsert(
				$request
					->convertTo(BulkItemUpsertRequestSchema::class)
					->merge([
						'create' => $create,
						'update' => $create,
					])
			)
		);
	}
}
