<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkFetchRpcHandler extends AbstractRpcHandler {
	protected $responseSchema = BulkSchema::class;

	public function handle(SmartDto $request) {
	}
}
