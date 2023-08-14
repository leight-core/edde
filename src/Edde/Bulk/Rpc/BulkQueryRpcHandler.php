<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc;

use Edde\Bulk\Schema\BulkSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkQueryRpcHandler extends AbstractRpcHandler {
	protected $responseSchema = BulkSchema::class;
	protected $responseSchemaArray = true;

	public function handle(SmartDto $request) {
	}
}