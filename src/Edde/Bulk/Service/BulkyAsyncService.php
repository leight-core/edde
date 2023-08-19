<?php
declare(strict_types=1);

namespace Edde\Bulk\Service;

use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;

class BulkyAsyncService extends AbstractAsyncService {
	protected function handle(SmartDto $job) {
		/** @var $identity SmartDto */
		$identity = $job->getValue('withRequest');
	}
}
