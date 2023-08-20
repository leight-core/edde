<?php
declare(strict_types=1);

namespace Edde\Bulk\Job;

use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Progress\IProgress;

class BulkImportAsyncService extends AbstractAsyncService {
	protected function handle(SmartDto $job, IProgress $progress, ?SmartDto $request) {
	}
}
