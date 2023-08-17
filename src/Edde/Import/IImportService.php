<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Dto\SmartDto;
use Edde\Job\Async\IAsyncService;

interface IImportService extends IAsyncService {
	/**
	 * Just a wrapper around "async" method for convenience.
	 *
	 * @param string $fileId
	 *
	 * @return SmartDto
	 */
	public function import(string $fileId): SmartDto;
}
