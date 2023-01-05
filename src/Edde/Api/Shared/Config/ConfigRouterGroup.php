<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Config;

use Edde\Api\Shared\Config\Endpoint\ConfigsEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class ConfigRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			ConfigsEndpoint::class,
		]);
	}
}
