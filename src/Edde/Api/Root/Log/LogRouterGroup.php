<?php
declare(strict_types=1);

namespace Edde\Api\Root\Log;

use Edde\Api\Root\Log\Endpoint\DropLogsEndpoint;
use Edde\Api\Root\Log\Endpoint\LogsEndpoint;
use Edde\Api\Root\Log\Endpoint\LogTagsEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class LogRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			DropLogsEndpoint::class,
			LogsEndpoint::class,
			LogTagsEndpoint::class,
		]);
	}
}
