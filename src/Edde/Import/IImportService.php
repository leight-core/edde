<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Job\Async\IAsyncService;
use Edde\Job\Dto\JobDto;

interface IImportService extends IAsyncService {
	/**
	 * Just a wrapper around "async" method for convenience.
	 *
	 * @param string $fileId
	 *
	 * @return JobDto
	 */
	public function import(string $fileId): JobDto;
}
