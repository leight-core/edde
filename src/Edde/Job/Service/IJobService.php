<?php
declare(strict_types=1);

namespace Edde\Job\Service;

use Edde\Doctrine\Exception\RequiredResultException;
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
	public function create(IAsyncService $asyncService, ?SmartDto $request): SmartDto;

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
}
