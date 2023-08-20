<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Mapper\BulkItemDtoMapperTrait;
use Edde\Bulk\Repository\BulkItemRepositoryTrait;
use Edde\Bulk\Schema\BulkItem\Internal\BulkItemUpsertRequestSchema;
use Edde\Database\Exception\RequiredResultException;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Exception\UserNotSelectedException;
use ReflectionException;

class BulkItemService {
	use SmartServiceTrait;
	use BulkItemRepositoryTrait;
	use BulkItemDtoMapperTrait;
	use CurrentUserServiceTrait;

	/**
	 * @param SmartDto $query
	 *
	 * @return SmartDto
	 * @throws RequiredResultException
	 * @throws SmartDtoException
	 * @throws ItemException
	 * @throws SkipException
	 */
	public function fetch(SmartDto $query): SmartDto {
		return $this->bulkItemDtoMapper->item(
			$this->bulkItemRepository->find($query->getValue('id'))
		);
	}

	/**
	 * @param SmartDto $query
	 *
	 * @return SmartDto[]
	 * @throws SmartDtoException
	 */
	public function query(SmartDto $query): array {
		return $this->bulkItemDtoMapper->map(
			$this->bulkItemRepository->query($query)
		);
	}

	/**
	 * @param SmartDto $query
	 *
	 * @return SmartDto
	 * @throws ItemException
	 * @throws \Edde\Database\Exception\RequiredResultException
	 * @throws SkipException
	 * @throws SmartDtoException
	 */
	public function delete(SmartDto $query): SmartDto {
		return $this->bulkItemDtoMapper->item(
			$this->bulkItemRepository->deleteBy($query)
		);
	}

	/**
	 * @param SmartDto $query
	 *
	 * @return SmartDto
	 * @throws ItemException
	 * @throws SkipException
	 * @throws SmartDtoException
	 * @throws UserNotSelectedException
	 * @throws ReflectionException
	 */
	public function upsert(SmartDto $query): SmartDto {
		return $this->bulkItemDtoMapper->item(
			$this->bulkItemRepository->upsert(
				$query
					->convertTo(BulkItemUpsertRequestSchema::class)
					->merge([
						'create' => [
							'status'  => 0,
							'created' => new DateTime(),
							'userId'  => $this->currentUserService->requiredId(),
						],
					])
			)
		);
	}

	public function total(SmartDto $query) {
		return $this->bulkItemRepository->total($query);
	}
}
