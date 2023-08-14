<?php
declare(strict_types=1);

namespace Edde\Bootstrap;

use Edde\Auth\Rpc\LoginRpcHandler;
use Edde\Auth\Rpc\LogoutRpcHandler;
use Edde\Auth\Rpc\TicketRpcHandler;
use Edde\Bulk\Rpc\BulkFetchRpcHandler;
use Edde\Bulk\Rpc\BulkQueryRpcHandler;
use Edde\Cache\Rpc\DropCacheRpcHandler;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Translation\Rpc\TranslationBundlesRpcHandler;

class BootstrapDefaultRpcIndex extends AbstractBootstrap {
	use RpcHandlerIndexTrait;

	public function bootstrap() {
		$this->rpcHandlerIndex->indexOf([
			LoginRpcHandler::class,
			LogoutRpcHandler::class,
			TicketRpcHandler::class,
			DropCacheRpcHandler::class,
			TranslationBundlesRpcHandler::class,
			BulkQueryRpcHandler::class,
			BulkFetchRpcHandler::class,
		]);
	}
}
