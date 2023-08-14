<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc;

use Edde\Bulk\Schema\BulkCreateSchema;
use Edde\Bulk\Schema\BulkSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkCreateRpcHandler extends AbstractRpcHandler {
	protected $responseSchema = BulkSchema::class;
	protected $requestSchema = BulkCreateSchema::class;
	protected $isMutator = true;
	protected $withForm = true;

	public function handle(SmartDto $request) {
	}
}
