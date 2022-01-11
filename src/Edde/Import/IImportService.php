<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Job\Dto\JobDto;
use Edde\Job\IJobService;

interface IImportService extends IJobService {
	/**
	 * Just a wrapper around "async" method for convenience.
	 *
	 * @param string $fileId
	 *
	 * @return JobDto
	 */
	public function import(string $fileId): JobDto;
}
