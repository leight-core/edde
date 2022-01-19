<?php
declare(strict_types=1);

namespace Edde\Api\Root\Cache\Endpoint;

use Edde\Cache\CacheTrait;
use Edde\Rest\Endpoint\AbstractMutationEndpoint;

/**
 * @description Drop all cache items.
 */
class DropCacheEndpoint extends AbstractMutationEndpoint {
	use CacheTrait;

	public function delete(): void {
		$this->cache->clear();
	}
}
