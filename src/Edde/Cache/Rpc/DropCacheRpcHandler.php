<?php
declare(strict_types=1);

namespace Edde\Cache\Rpc;

use Edde\Cache\CacheTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class DropCacheRpcHandler extends AbstractRpcHandler {
	use CacheTrait;

	protected $requestSchemaOptional = true;
	protected $responseSchemaOptional = true;
	protected $isMutator = true;

	public function handle(SmartDto $request) {
		$this->cache->clear();
	}
}
