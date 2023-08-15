<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Mapper\BulkDtoMapperTrait;
use Edde\Bulk\Repository\BulkRepositoryTrait;
use Edde\Bulk\Schema\Bulk\BulkCreateSchema;
use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkCreateRpcHandler extends AbstractRpcHandler {
	use BulkRepositoryTrait;
	use BulkDtoMapperTrait;

	protected $responseSchema = BulkSchema::class;
	protected $requestSchema = BulkCreateSchema::class;
	protected $isMutator = true;
	protected $withForm = true;

	public function handle(SmartDto $request) {
		return $this->bulkDtoMapper->item($this->bulkRepository->save($request));
	}
}
