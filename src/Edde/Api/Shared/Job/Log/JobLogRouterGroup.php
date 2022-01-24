<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job\Log;

use Edde\Api\Shared\Job\Log\Endpoint\JobLogsEndpoint;
use Edde\Api\Shared\Job\Log\Endpoint\LevelsEndpoint;
use Edde\Api\Shared\Job\Log\Endpoint\TypesEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class JobLogRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			JobLogsEndpoint::class,
			LevelsEndpoint::class,
			TypesEndpoint::class,
		]);
	}
}
