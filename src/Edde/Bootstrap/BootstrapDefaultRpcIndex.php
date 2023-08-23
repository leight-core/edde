<?php
declare(strict_types=1);

namespace Edde\Bootstrap;

use Edde\Auth\Rpc\LoginRpcHandler;
use Edde\Auth\Rpc\LogoutRpcHandler;
use Edde\Auth\Rpc\TicketRpcHandler;
use Edde\Bulk\Rpc\Bulk\BulkCommitRpcHandler;
use Edde\Bulk\Rpc\Bulk\BulkCreateRpcHandler;
use Edde\Bulk\Rpc\Bulk\BulkDeleteRpcHandler;
use Edde\Bulk\Rpc\Bulk\BulkFetchRpcHandler;
use Edde\Bulk\Rpc\Bulk\BulkImportRpcHandler;
use Edde\Bulk\Rpc\Bulk\BulkQueryRpcHandler;
use Edde\Bulk\Rpc\BulkItem\BulkItemCountRpcHandler;
use Edde\Bulk\Rpc\BulkItem\BulkItemDeleteRpcHandler;
use Edde\Bulk\Rpc\BulkItem\BulkItemFetchRpcHandler;
use Edde\Bulk\Rpc\BulkItem\BulkItemQueryRpcHandler;
use Edde\Bulk\Rpc\BulkItem\BulkItemUpsertRpcHandler;
use Edde\Cache\Rpc\DropCacheRpcHandler;
use Edde\Job\Rpc\JobFetchRpcHandler;
use Edde\Job\Rpc\JobFindByRpcHandler;
use Edde\Job\Rpc\JobQueryRpcHandler;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Translation\Rpc\TranslationBundlesRpcHandler;
use Edde\Upgrade\Rpc\UpgradeQueryRpcHandler;
use Edde\Upgrade\Rpc\UpgradeRpcHandler;

class BootstrapDefaultRpcIndex extends AbstractBootstrap {
    use RpcHandlerIndexTrait;

    public function bootstrap() {
        $this->rpcHandlerIndex->indexOf([
            BulkCreateRpcHandler::class,
            BulkDeleteRpcHandler::class,
            BulkImportRpcHandler::class,
            BulkItemDeleteRpcHandler::class,
            BulkItemFetchRpcHandler::class,
            BulkItemQueryRpcHandler::class,
            BulkItemUpsertRpcHandler::class,
            DropCacheRpcHandler::class,
            JobFetchRpcHandler::class,
            JobQueryRpcHandler::class,
            LoginRpcHandler::class,
            LogoutRpcHandler::class,
            TicketRpcHandler::class,
            TranslationBundlesRpcHandler::class,
            UpgradeQueryRpcHandler::class,
            UpgradeRpcHandler::class,
            BulkCommitRpcHandler::class,
            BulkFetchRpcHandler::class,
            BulkItemCountRpcHandler::class,
            BulkQueryRpcHandler::class,
            JobFindByRpcHandler::class,
        ]);
    }
}
