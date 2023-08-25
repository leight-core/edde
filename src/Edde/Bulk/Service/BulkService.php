<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use DateTime;
use Edde\Bulk\Repository\BulkItemRepositoryTrait;
use Edde\Bulk\Repository\BulkRepositoryTrait;
use Edde\Bulk\Schema\Bulk\Internal\BulkCreateSchema;
use Edde\Bulk\Schema\Bulk\Internal\BulkUpdateRequestSchema;
use Edde\Bulk\Schema\BulkItem\Query\BulkItemQuerySchema;
use Edde\Database\Exception\RepositoryException;
use Edde\Database\Exception\RequiredResultException;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Dto\SmartServiceTrait;
use Edde\Job\Repository\JobRepositoryTrait;
use Edde\Job\Schema\Job\Query\JobQuerySchema;
use Edde\User\CurrentUserServiceTrait;
use Edde\User\Exception\UserNotSelectedException;

class BulkService {
    use CurrentUserServiceTrait;
    use BulkRepositoryTrait;
    use BulkItemRepositoryTrait;
    use JobRepositoryTrait;
    use SmartServiceTrait;

    public function find(string $id): SmartDto {
        return $this->bulkRepository->find($id);
    }

    /**
     * @param SmartDto $request
     *
     * @return SmartDto
     * @throws SmartDtoException
     * @throws UserNotSelectedException
     */
    public function create(SmartDto $request): SmartDto {
        return $this->bulkRepository->create(
            $request
                ->convertTo(BulkCreateSchema::class)
                ->merge([
                    'created' => new DateTime(),
                    'status'  => 0,
                    'commit'  => false,
                    'userId'  => $this->currentUserService->requiredId(),
                ])
        );
    }

    /**
     * @param SmartDto $request
     *
     * @return SmartDto
     * @throws RequiredResultException
     * @throws SmartDtoException
     */
    public function fetch(SmartDto $request): SmartDto {
        return $this->bulkRepository->find($request->getValue('id'));
    }

    /**
     * @param SmartDto $request
     *
     * @return SmartDto
     * @throws RequiredResultException
     * @throws SmartDtoException
     */
    public function delete(SmartDto $request): SmartDto {
        return $this->bulkRepository->deleteBy($request);
    }

    /**
     * @param SmartDto $request
     *
     * @return SmartDto
     * @throws SmartDtoException
     * @throws RepositoryException
     * @throws RequiredResultException
     */
    public function commit(SmartDto $request): void {
        $this->bulkItemRepository->deleteWith(
            $this->smartService->from(
                [
                    'filter' => [
                        'bulkId' => $request->getValue('id'),
                    ],
                ],
                BulkItemQuerySchema::class,
            )
        );
        $this->bulkItemRepository->deleteWith(
            $this->smartService->from(
                [
                    'filter' => [
                        'reference' => $request->getValue('id'),
                    ],
                ],
                JobQuerySchema::class,
            )
        );
        $this->bulkRepository->update(
            $request
                ->convertTo(BulkUpdateRequestSchema::class)
                ->merge(
                    [
                        'update' => [
                            'commit' => true,
                        ],
                        'filter' => [
                            'id' => $request->getValue('id'),
                        ],
                    ],
                    true
                )
        );
    }

    /**
     * @param SmartDto $request
     *
     * @return SmartDto[]
     * @throws SmartDtoException
     */
    public function query(SmartDto $request): array {
        return $this->bulkRepository->query($request);
    }

    public function update(SmartDto $update): SmartDto {
        return $this->bulkRepository->update($update);
    }

    public function withStatus(string $bulkId, int $status): SmartDto {
        return $this->bulkRepository->update(
            $this->smartService->from(
                [
                    'update' => [
                        'status' => $status,
                    ],
                    'filter' => [
                        'id' => $bulkId,
                    ],
                ],
                BulkUpdateRequestSchema::class)
        );
    }
}
