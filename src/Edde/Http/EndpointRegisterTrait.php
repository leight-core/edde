<?php
declare(strict_types=1);

namespace Edde\Http;

use Edde\Container\ContainerTrait;
use Edde\Http\Service\HttpIndexTrait;
use Slim\Interfaces\RouteCollectorProxyInterface;

trait EndpointRegisterTrait {
	use ContainerTrait;
	use HttpIndexTrait;

	/**
	 * Register the given array of endpoints (into router and also into DiscoveryService).
	 *
	 * @param RouteCollectorProxyInterface $routeCollectorProxy
	 * @param array                        $endpoints
	 * @param string[]                     $routerGroups
	 */
	public function endpoints(RouteCollectorProxyInterface $routeCollectorProxy, array $endpoints, array $routerGroups = []): void {
		foreach ($endpoints as $endpoint) {
			$this->httpIndex->register($endpoint);
		}
		foreach ($routerGroups as $routerGroup) {
			$this->container->get($routerGroup)->register($routeCollectorProxy);
		}
	}
}
