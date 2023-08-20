<?php
declare(strict_types=1);

namespace Edde\Bulk\Job;

use Edde\Bulk\Exception\BulkImportException;
use Edde\Bulk\Schema\BulkItem\BulkItemQuerySchema;
use Edde\Bulk\Service\BulkItemServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Job\Async\AbstractAsyncService;
use Edde\Progress\IProgress;
use Edde\Query\Schema\WithIdentitySchema;

class BulkImportAsyncService extends AbstractAsyncService {
	use BulkItemServiceTrait;

	protected function handle(SmartDto $job, IProgress $progress, ?SmartDto $request) {
		if (!$request) {
			throw new BulkImportException(sprintf('Cannot start bulk import (job [%s]), missing bulk ID (schema: [%s]).', $job->getValue('id'), WithIdentitySchema::class));
		}
		$query = $this->smartService->from(
			[
				'filter' => [
					'bulkId' => $request->getValue('id'),
				],
			],
			BulkItemQuerySchema::class
		);
		$progress->onStart(
			$this->bulkItemService->total($query)
		);
		foreach ($this->bulkItemService->query($query) as $bulkItem) {
			$a = $bulkItem;
		}
	}
}
