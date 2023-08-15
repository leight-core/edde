<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Service\BulkServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\AbstractRpcHandler;

class BulkCommitRpcHandler extends AbstractRpcHandler {
	use BulkServiceTrait;

	protected $requestSchema = WithIdentitySchema::class;

	public function handle(SmartDto $request) {
		$this->bulkService->commit($request);
	}
}
