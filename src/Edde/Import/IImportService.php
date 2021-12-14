<?php
declare(strict_types=1);

namespace Edde\Import;

use Edde\Job\IJobService;
use Edde\Progress\IProgress;

/**
 * Just a common interface for everything doing an import into the system.
 */
interface IImportService extends IJobService {
	/**
	 * Just do the import; implementation have to understand the given file format.
	 *
	 * @param string         $file     file to be imported
	 * @param mixed          $params
	 * @param IProgress|null $progress if there is need to see the progress of import
	 */
	public function import(string $file, $params = null, IProgress $progress = null);
}
