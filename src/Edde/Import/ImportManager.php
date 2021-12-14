<?php
declare(strict_types=1);

namespace Edde\Import;

use DI\DependencyException;
use DI\NotFoundException;
use Edde\Container\ContainerTrait;
use Edde\Job\Dto\JobDto;
use Edde\Job\JobExecutorTrait;

class ImportManager {
	use JobExecutorTrait;
	use ContainerTrait;

	/**
	 * @param string $service
	 * @param string $fileId
	 *
	 * @return JobDto
	 *
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function import(string $service, string $fileId): JobDto {
		return $this->jobExecutor->execute(
			$this->container->get($service),
			$fileId
		);
	}
}
