<?php
declare(strict_types=1);

namespace Edde\Bulk\Job;

use Edde\Job\Async\AbstractAsyncService;
use Edde\Job\IJob;

class BulkJobService extends AbstractAsyncService {
	protected function handle(IJob $job) {
	}
}
