<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Schema\Bulk\BulkCreateSchema;
use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Bulk\Schema\Bulk\BulkValuesSchema;
use Edde\Bulk\Service\BulkServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkCreateRpcHandler extends AbstractRpcHandler {
    use BulkServiceTrait;

    protected $requestSchema = BulkCreateSchema::class;
    protected $responseSchema = BulkSchema::class;
    protected $valuesSchema = BulkValuesSchema::class;
    protected $withForm = true;
    protected $invalidators = [
        BulkQueryRpcHandler::class,
        BulkFetchRpcHandler::class,
        BulkCountRpcHandler::class,
    ];

    public function handle(SmartDto $request) {
        return $this->bulkService->create($request);
    }
}
