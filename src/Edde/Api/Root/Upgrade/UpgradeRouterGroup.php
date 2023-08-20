<?php
declare(strict_types=1);

namespace Edde\Api\Root\Upgrade;

use Edde\Api\Root\Upgrade\Endpoint\RunEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class UpgradeRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			RunEndpoint::class,
		]);
	}
}
