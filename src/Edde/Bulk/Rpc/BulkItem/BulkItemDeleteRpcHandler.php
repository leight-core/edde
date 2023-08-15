<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\BulkItem;

use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Bulk\Service\BulkItemServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemDeleteRpcHandler extends AbstractRpcHandler {
	use BulkItemServiceTrait;

	protected $requestSchema = WithIdentitySchema::class;
	protected $responseSchema = BulkItemSchema::class;
	protected $isMutator = true;
	protected $invalidators = [
		BulkItemQueryRpcHandler::class,
		BulkItemFetchRpcHandler::class,
	];

	public function handle(SmartDto $request) {
		return $this->bulkItemService->delete($request);
	}
}
