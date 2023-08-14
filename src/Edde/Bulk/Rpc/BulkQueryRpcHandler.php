<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc;

use Edde\Bulk\Mapper\BulkDtoMapperTrait;
use Edde\Bulk\Repository\BulkRepositoryTrait;
use Edde\Bulk\Schema\BulkSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkQueryRpcHandler extends AbstractRpcHandler {
	use BulkRepositoryTrait;
	use BulkDtoMapperTrait;

	protected $responseSchema = BulkSchema::class;
	protected $responseSchemaArray = true;

	public function handle(SmartDto $request) {
		return $this->bulkDtoMapper->map($this->bulkRepository->withQuery('b', $request));
	}
}
