<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\BulkItem;

use Edde\Bulk\Service\BulkItemServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemDeleteRpcHandler extends AbstractRpcHandler {
	use BulkItemServiceTrait;

	public function handle(SmartDto $request) {
		return $this->bulkItemService->delete($request);
	}
}
