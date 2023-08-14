<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\BulkItem;

use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemQueryRpcHandler extends AbstractRpcHandler {
	protected $responseSchema = BulkItemSchema::class;
	protected $responseSchemaArray = true;

	public function handle(SmartDto $request) {
	}
}
