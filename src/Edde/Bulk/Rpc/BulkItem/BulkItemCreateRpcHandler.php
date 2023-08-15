<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\BulkItem;

use Edde\Bulk\Schema\BulkItem\BulkItemCreateSchema;
use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Bulk\Service\BulkItemServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemCreateRpcHandler extends AbstractRpcHandler {
	use BulkItemServiceTrait;

	protected $requestSchema = BulkItemCreateSchema::class;
	protected $responseSchema = BulkItemSchema::class;
	protected $isMutator = true;
	protected $withForm = true;
	protected $invalidators = [
		BulkItemQueryRpcHandler::class,
		BulkItemFetchRpcHandler::class,
	];

	public function handle(SmartDto $request) {
		return $this->bulkItemService->create($request);
	}
}
