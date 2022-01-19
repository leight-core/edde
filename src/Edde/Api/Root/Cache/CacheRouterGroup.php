<?php
declare(strict_types=1);

namespace Edde\Api\Root\Cache;

use Edde\Api\Root\Cache\Endpoint\DropCacheEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class CacheRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			DropCacheEndpoint::class,
		]);
	}
}
