<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Repository\BulkItemRepositoryTrait;
use Edde\Bulk\Schema\BulkItem\DB\BulkItemUpsertSchema;
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
		return $this->bulkItemRepository->find($query->getValue('id'));
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
	public function upsert(SmartDto $query, bool $raw = false): SmartDto {
		return $this->bulkItemRepository->upsert(
			$query
				->convertTo(BulkItemUpsertSchema::class, $raw)
				->merge([
					'create' => [
						'status'  => 0,
						'created' => new DateTime(),
						'userId'  => $this->currentUserService->requiredId(),
					],
				])
		);
	}
}
