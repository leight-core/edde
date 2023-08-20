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
use Edde\Bulk\Rpc\BulkItem\BulkItemDeleteRpcHandler;
use Edde\Bulk\Rpc\BulkItem\BulkItemFetchRpcHandler;
use Edde\Bulk\Rpc\BulkItem\BulkItemQueryRpcHandler;
use Edde\Bulk\Rpc\BulkItem\BulkItemUpsertRpcHandler;
use Edde\Cache\Rpc\DropCacheRpcHandler;
use Edde\Job\Rpc\JobFetchRpcHandler;
use Edde\Job\Rpc\JobQueryRpcHandler;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Translation\Rpc\TranslationBundlesRpcHandler;
use Edde\Upgrade\Rpc\UpgradeQueryRpcHandler;
use Edde\Upgrade\Rpc\UpgradeRpcHandler;

class BootstrapDefaultRpcIndex extends AbstractBootstrap {
	use RpcHandlerIndexTrait;

	public function bootstrap() {
		$this->rpcHandlerIndex->indexOf([
			LoginRpcHandler::class,
			LogoutRpcHandler::class,
			TicketRpcHandler::class,
			DropCacheRpcHandler::class,
			TranslationBundlesRpcHandler::class,
			JobFetchRpcHandler::class,
			JobQueryRpcHandler::class,
			BulkCreateRpcHandler::class,
			BulkQueryRpcHandler::class,
			BulkFetchRpcHandler::class,
			BulkCommitRpcHandler::class,
			BulkDeleteRpcHandler::class,
			BulkImportRpcHandler::class,
			BulkItemQueryRpcHandler::class,
			BulkItemDeleteRpcHandler::class,
			BulkItemUpsertRpcHandler::class,
			BulkItemFetchRpcHandler::class,
			UpgradeRpcHandler::class,
			UpgradeQueryRpcHandler::class,
		]);
	}
}
