<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Repository\BulkRepositoryTrait;
use Edde\Bulk\Schema\Bulk\Query\BulkQuerySchema;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkCountRpcHandler extends AbstractRpcHandler {
    use BulkRepositoryTrait;

    protected $requestSchema = BulkQuerySchema::class;

    public function handle(SmartDto $request) {
        return $this->bulkRepository->total($request);
    }
}
