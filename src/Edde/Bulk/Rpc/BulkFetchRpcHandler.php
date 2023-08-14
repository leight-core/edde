<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc;

use Edde\Bulk\Schema\BulkSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkFetchRpcHandler extends AbstractRpcHandler {
	protected $responseSchema = BulkSchema::class;

	public function handle(SmartDto $request) {
	}
}
