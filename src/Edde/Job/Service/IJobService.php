<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Database\Exception\RequiredResultException;
use Edde\Dto\Exception\SmartDtoException;
use Edde\Dto\SmartDto;
use Edde\Job\Async\IAsyncService;
use Edde\Mapper\Exception\ItemException;
use Edde\Mapper\Exception\SkipException;
use Edde\User\Exception\UserNotSelectedException;
use ReflectionException;

interface IJobService {
    /**
     * @param IAsyncService $asyncService
     * @param SmartDto|null $request
     *
     * @return SmartDto
     * @throws UserNotSelectedException
     * @throws SkipException
     * @throws ItemException
     * @throws SmartDtoException
     * @throws ReflectionException
     */
    public function create(IAsyncService $asyncService, ?SmartDto $request, string $reference = null): SmartDto;

    /**
     * @param SmartDto $request
     *
     * @return SmartDto[]
     */
    public function query(SmartDto $request): array;

    public function update(SmartDto $patch): SmartDto;

    /**
     * @param string $jobId
     *
     * @return SmartDto
     * @throws RequiredResultException
     * @throws SkipException
     * @throws ItemException
     */
    public function find(string $jobId): SmartDto;

    public function commit(SmartDto $request): SmartDto;
}
