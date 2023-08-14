<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc;

use Edde\Bulk\Schema\BulkItemSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemFetchRpcHandler extends AbstractRpcHandler {
	protected $responseSchema = BulkItemSchema::class;

	public function handle(SmartDto $request) {
	}
}
