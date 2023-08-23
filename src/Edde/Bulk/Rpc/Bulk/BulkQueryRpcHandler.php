<?php
declare(strict_types=1);

namespace Edde\Bulk\Rpc\Bulk;

use Edde\Bulk\Schema\Bulk\BulkSchema;
use Edde\Bulk\Schema\Bulk\Query\BulkFilterSchema;
use Edde\Bulk\Schema\Bulk\Query\BulkOrderBySchema;
use Edde\Bulk\Schema\Bulk\Query\BulkQuerySchema;
use Edde\Bulk\Service\BulkServiceTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class BulkQueryRpcHandler extends AbstractRpcHandler {
    use BulkServiceTrait;

    protected $requestSchema = BulkQuerySchema::class;
    protected $filterSchema = BulkFilterSchema::class;
    protected $orderBySchema = BulkOrderBySchema::class;
    protected $responseSchema = BulkSchema::class;
    protected $responseSchemaArray = true;
    protected $isQuery = true;
    protected $meta = [
        'withCountQuery' => BulkCountRpcHandler::class,
    ];

    public function handle(SmartDto $request) {
        return $this->bulkService->query($request);
    }
}
