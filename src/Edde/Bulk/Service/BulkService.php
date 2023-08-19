<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Mapper\BulkDtoMapperTrait;
use Edde\Bulk\Repository\BulkRepositoryTrait;
use Edde\Bulk\Schema\Bulk\Internal\BulkCreateSchema;
use Edde\Bulk\Schema\Bulk\Internal\BulkPatchRequestSchema;
use Edde\Doctrine\Exception\RepositoryException;
use Edde\Doctrine\Exception\RequiredResultException;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Exception\UserNotSelectedException;
use ReflectionException;

class BulkService {
	use CurrentUserServiceTrait;
	use BulkRepositoryTrait;
	use BulkDtoMapperTrait;
	use SmartServiceTrait;
	use BulkAsyncServiceTrait;

	public function get(string $id): SmartDto {
		return $this->bulkDtoMapper->item($this->bulkRepository->find($id));
	}

	/**
	 * @param SmartDto $request
	 *
	 * @return SmartDto
	 * @throws SmartDtoException
	 * @throws ItemException
	 * @throws SkipException
	 * @throws UserNotSelectedException
	 * @throws ReflectionException
	 */
	public function create(SmartDto $request): SmartDto {
		return $this->bulkDtoMapper->item(
			$this->bulkRepository->save(
				$request
					->convertTo(BulkCreateSchema::class)
					->merge([
						'created' => new DateTime(),
						'status'  => 0,
						'commit'  => false,
						'userId'  => $this->currentUserService->requiredId(),
					])
			)
		);
	}

	/**
	 * @param SmartDto $request
	 *
	 * @return SmartDto
	 * @throws ItemException
	 * @throws RequiredResultException
	 * @throws SkipException
	 * @throws SmartDtoException
	 */
	public function fetch(SmartDto $request): SmartDto {
		return $this->bulkDtoMapper->item($this->bulkRepository->find($request->getValue('id')));
	}

	/**
	 * @param SmartDto $request
	 *
	 * @return SmartDto
	 * @throws ItemException
	 * @throws RequiredResultException
	 * @throws SkipException
	 * @throws SmartDtoException
	 */
	public function delete(SmartDto $request): SmartDto {
		return $this->bulkDtoMapper->item($this->bulkRepository->deleteBy($request));
	}

	/**
	 * @param SmartDto $request
	 *
	 * @return void
	 * @throws ReflectionException
	 * @throws SmartDtoException
	 * @throws RepositoryException
	 * @throws RequiredResultException
	 */
	public function commit(SmartDto $request) {
		$this->bulkRepository->patch(
			$request
				->convertTo(BulkPatchRequestSchema::class)
				->merge([
					'patch'  => [
						'commit' => true,
					],
					'filter' => [
						'id' => $request->getValue('id'),
					],
				], true)
		);
	}

	/**
	 * @param SmartDto $request
	 *
	 * @return array
	 * @throws SmartDtoException
	 */
	public function query(SmartDto $request): array {
		return $this->bulkDtoMapper->map($this->bulkRepository->query('b', $request));
	}

	public function import(SmartDto $bulk): SmartDto {
		return $this->bulkAsyncService->async($bulk);
	}
}
