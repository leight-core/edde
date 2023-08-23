<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\BulkItem;

use Edde\Bulk\Repository\BulkItemRepositoryTrait;
use Edde\Bulk\Schema\BulkItem\Query\BulkItemQuerySchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemCountRpcHandler extends AbstractRpcHandler {
    use BulkItemRepositoryTrait;

    protected $requestSchema = BulkItemQuerySchema::class;

    public function handle(SmartDto $request) {
        return $this->bulkItemRepository->total($request);
    }
}
