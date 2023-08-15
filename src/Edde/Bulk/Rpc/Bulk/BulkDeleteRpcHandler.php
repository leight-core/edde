<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Bulk\Service\BulkServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\AbstractRpcHandler;

class BulkDeleteRpcHandler extends AbstractRpcHandler {
	use BulkServiceTrait;

	protected $requestSchema = WithIdentitySchema::class;
	protected $responseSchema = BulkSchema::class;
	protected $isMutator = true;

	public function handle(SmartDto $request) {
		return $this->bulkService->delete($request);
	}
}
