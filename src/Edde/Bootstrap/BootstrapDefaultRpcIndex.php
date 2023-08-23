<?php
declare(strict_types=1);

namespace Edde\Bootstrap;

use Edde\Auth\Rpc\LoginRpcHandler;
use Edde\Auth\Rpc\LogoutRpcHandler;
use Edde\Auth\Rpc\TicketRpcHandler;
use Edde\Bulk\Rpc\Bulk\BulkCommitRpcHandler;
use Edde\Bulk\Rpc\Bulk\BulkCountRpcHandler;
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
use Edde\Job\Rpc\JobCountRpcHandler;
use Edde\Job\Rpc\JobFetchRpcHandler;
use Edde\Job\Rpc\JobFindByRpcHandler;
use Edde\Job\Rpc\JobQueryRpcHandler;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Translation\Rpc\TranslationBundlesRpcHandler;
use Edde\Upgrade\Rpc\UpgradeCountRpcHandler;
use Edde\Upgrade\Rpc\UpgradeQueryRpcHandler;
use Edde\Upgrade\Rpc\UpgradeRpcHandler;

class BootstrapDefaultRpcIndex extends AbstractBootstrap {
    use RpcHandlerIndexTrait;

    public function bootstrap() {
        $this->rpcHandlerIndex->indexOf([
            BulkCommitRpcHandler::class,
            BulkCountRpcHandler::class,
            BulkCreateRpcHandler::class,
            BulkDeleteRpcHandler::class,
            BulkFetchRpcHandler::class,
            BulkImportRpcHandler::class,
            BulkItemCountRpcHandler::class,
            BulkItemDeleteRpcHandler::class,
            BulkItemFetchRpcHandler::class,
            BulkItemQueryRpcHandler::class,
            BulkItemUpsertRpcHandler::class,
            BulkQueryRpcHandler::class,
            DropCacheRpcHandler::class,
            JobCountRpcHandler::class,
            JobFetchRpcHandler::class,
            JobFindByRpcHandler::class,
            JobQueryRpcHandler::class,
            LoginRpcHandler::class,
            LogoutRpcHandler::class,
            TicketRpcHandler::class,
            TranslationBundlesRpcHandler::class,
            UpgradeCountRpcHandler::class,
            UpgradeQueryRpcHandler::class,
            UpgradeRpcHandler::class,
        ]);
    }
}
