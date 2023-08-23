<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\BulkItem;

use Edde\Bulk\Repository\BulkItemRepositoryTrait;
use Edde\Bulk\Schema\BulkItem\BulkItemSchema;
use Edde\Dto\SmartDto;
use Edde\Query\Schema\WithIdentitySchema;
use Edde\Rpc\AbstractRpcHandler;

class BulkItemDeleteRpcHandler extends AbstractRpcHandler {
    use BulkItemRepositoryTrait;

    protected $requestSchema = WithIdentitySchema::class;
    protected $responseSchema = BulkItemSchema::class;
    protected $isMutator = true;
    protected $invalidators = [
        BulkItemQueryRpcHandler::class,
        BulkItemFetchRpcHandler::class,
        BulkItemCountRpcHandler::class,
    ];

    public function handle(SmartDto $request) {
        return $this->bulkItemRepository->deleteBy($request);
    }
}
