<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Service\BulkServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Job\Schema\Job\JobSchema;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\AbstractRpcHandler;

class BulkJobRpcHandler extends AbstractRpcHandler {
	use BulkServiceTrait;

	protected $requestSchema = WithIdentitySchema::class;
	protected $responseSchema = JobSchema::class;

//	protected $responseSchema=

	public function handle(SmartDto $request) {
		return $this->bulkService->job($request);
	}
}
