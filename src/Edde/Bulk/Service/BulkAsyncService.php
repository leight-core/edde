<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Progress\IProgress;

class BulkAsyncService extends AbstractAsyncService {
	protected function handle(SmartDto $job, IProgress $progress, ?SmartDto $request) {
	}
}
