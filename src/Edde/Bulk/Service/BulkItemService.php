<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Mapper\BulkItemDtoMapperTrait;
use Edde\Bulk\Repository\BulkItemRepositoryTrait;
use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\User\CurrentUserServiceTrait;

class BulkItemService {
	use SmartServiceTrait;
	use BulkItemRepositoryTrait;
	use BulkItemDtoMapperTrait;
	use CurrentUserServiceTrait;

	public function create(SmartDto $request): SmartDto {
		return $this->bulkItemDtoMapper->item(
			$this->bulkItemRepository->save(
				$this->smartService->cloneTo($request, BulkItemSchema::class, [
					'created' => new DateTime(),
					'status'  => 0,
					'commit'  => false,
					'userId'  => $this->currentUserService->requiredId(),
				])
			)
		);
	}

	public function fetch(SmartDto $request): SmartDto {
		return $this->bulkItemDtoMapper->item($this->bulkItemRepository->find($request->getValue('id')));
	}

	public function query(SmartDto $request): array {
		return $this->bulkItemDtoMapper->map($this->bulkItemRepository->withQuery('b', $request));
	}

	public function delete(SmartDto $request): SmartDto {
		return $this->bulkItemDtoMapper->item($this->bulkItemRepository->deleteBy($request));
	}

	public function upsert(SmartDto $request): SmartDto {
		return $this->bulkItemDtoMapper->item($this->bulkItemRepository->upsert($request));
	}
}