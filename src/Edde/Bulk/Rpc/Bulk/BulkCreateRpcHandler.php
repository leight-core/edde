<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Schema\Bulk\BulkCreateSchema;
use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Bulk\Service\BulkServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkCreateRpcHandler extends AbstractRpcHandler {
	use BulkServiceTrait;

	protected $responseSchema = BulkSchema::class;
	protected $requestSchema = BulkCreateSchema::class;
	protected $isMutator = true;
	protected $withForm = true;
	protected $invalidators = [
		BulkQueryRpcHandler::class,
		BulkFetchRpcHandler::class,
	];

	public function handle(SmartDto $request) {
		return $this->bulkService->create($request);
	}
}
