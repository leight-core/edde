<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Job\BulkImportAsyncServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\AbstractRpcHandler;

class BulkImportRpcHandler extends AbstractRpcHandler {
	use BulkImportAsyncServiceTrait;

	protected $requestSchema = WithIdentitySchema::class;
	protected $responseSchema = JobSchema::class;
	protected $isMutator = true;

	public function handle(SmartDto $request) {
		return $this->bulkImportAsyncService->async($request);
	}
}
