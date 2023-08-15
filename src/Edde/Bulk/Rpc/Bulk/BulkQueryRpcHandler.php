<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Schema\Bulk\BulkFilterSchema;
use Edde\Bulk\Schema\Bulk\BulkOrderBySchema;
use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Bulk\Service\BulkServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkQueryRpcHandler extends AbstractRpcHandler {
	use BulkServiceTrait;

	protected $filterSchema = BulkFilterSchema::class;
	protected $orderBySchema = BulkOrderBySchema::class;
	protected $responseSchema = BulkSchema::class;
	protected $responseSchemaArray = true;
	protected $isQuery = true;

	public function handle(SmartDto $request) {
		return $this->bulkService->query($request);
	}
}
