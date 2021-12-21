<?php
declare(strict_types=1);

namespace Edde\Import;

use DI\DependencyException;
use DI\NotFoundException;
use Edde\Job\Dto\JobDto;

interface IImportManager {
	/**
	 * @param string $service
	 * @param string $fileId
	 *
	 * @return JobDto
	 *
	 * @throws DependencyException
	 * @throws NotFoundException
	 */
	public function import(string $service, string $fileId): JobDto;
}
