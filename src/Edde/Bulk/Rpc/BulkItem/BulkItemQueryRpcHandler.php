<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\BulkItem;

use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Bulk\Schema\BulkItem\Query\BulkItemFilterSchema;
use Edde\Bulk\Schema\BulkItem\Query\BulkItemOrderBySchema;
use Edde\Bulk\Schema\BulkItem\Query\BulkItemQuerySchema;
use Edde\Bulk\Service\BulkItemServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemQueryRpcHandler extends AbstractRpcHandler {
	use BulkItemServiceTrait;

	protected $filterSchema = BulkItemFilterSchema::class;
	protected $orderBySchema = BulkItemOrderBySchema::class;
	protected $requestSchema = BulkItemQuerySchema::class;
	protected $responseSchema = BulkItemSchema::class;
	protected $responseSchemaArray = true;
	protected $isQuery = true;

	public function handle(SmartDto $request) {
		return $this->bulkItemService->query($request);
	}
}
