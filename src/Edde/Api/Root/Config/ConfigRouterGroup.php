<?php
declare(strict_types=1);

namespace Edde\Api\Root\Config;

use Edde\Api\Root\Config\Endpoint\ConfigEndpoint;
use Edde\Api\Root\Config\Endpoint\ConfigsEndpoint;
use Edde\Api\Root\Config\Endpoint\CreateEndpoint;
use Edde\Api\Root\Config\Endpoint\DeleteEndpoint;
use Edde\Api\Root\Config\Endpoint\PatchEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class ConfigRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			CreateEndpoint::class,
			DeleteEndpoint::class,
			ConfigsEndpoint::class,
			PatchEndpoint::class,
			ConfigEndpoint::class,
		]);
	}
}
