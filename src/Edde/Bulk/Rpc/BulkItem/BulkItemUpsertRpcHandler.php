<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\BulkItem;

use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Bulk\Schema\BulkItem\BulkItemUpsertSchema;
use Edde\Bulk\Service\BulkItemServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemUpsertRpcHandler extends AbstractRpcHandler {
	use BulkItemServiceTrait;

	protected $requestSchema = BulkItemUpsertSchema::class;
	protected $responseSchema = BulkItemSchema::class;
	protected $isMutator = true;

	protected $invalidators = [
		BulkItemQueryRpcHandler::class,
		BulkItemFetchRpcHandler::class,
	];

	public function handle(SmartDto $request) {
		return $this->bulkItemService->upsert($request);
	}
}
