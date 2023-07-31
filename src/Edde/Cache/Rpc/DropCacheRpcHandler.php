<?php
declare(strict_types=1);

namespace Edde\Cache\Rpc;

use Edde\Cache\CacheTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;

class DropCacheRpcHandler extends AbstractRpcHandler {
	use CacheTrait;

	public function handle(SmartDto $request): ?SmartDto {
		$this->cache->clear();
		return null;
	}
}
