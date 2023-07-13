<?php
declare(strict_types=1);

namespace Edde\Api\Root\Sdk\Endpoint;

use Edde\Cache\CacheTrait;
use Edde\Rest\Endpoint\AbstractEndpoint;
use Edde\Sdk\SdkTrait;

/**
 * @description Magical endpoint which gets current client SDK in TypeScript.
 * @internal
 */
class DownloadEndpoint extends AbstractEndpoint {
	use SdkTrait;
	use CacheTrait;

	public function get(): void {
		$this->cache->clear();
		$this->sdk->zip();
		@die();
	}
}
