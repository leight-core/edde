<?php
declare(strict_types=1);

namespace Edde\Cache\Rpc;

use Edde\Cache\CacheTrait;
use Edde\Dto\SmartDto;
use Edde\Rpc\AbstractRpcHandler;
use Edde\Rpc\Utils\WithMutator;
use Edde\Rpc\Utils\WithOptionalRequestSchema;
use Edde\Rpc\Utils\WithOptionalResponseSchema;

class DropCacheRpcHandler extends AbstractRpcHandler {
	use CacheTrait;

	use WithMutator;
	use WithOptionalRequestSchema;
	use WithOptionalResponseSchema;

	public function handle(SmartDto $request) {
		$this->cache->clear();
	}
}
