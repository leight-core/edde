<?php
declare(strict_types=1);

namespace Edde\Api\Root\Job;

use Edde\Api\Root\Job\Endpoint\ExecuteEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class JobRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			ExecuteEndpoint::class,
		]);
	}
}
