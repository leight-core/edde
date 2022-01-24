<?php
declare(strict_types=1);

namespace Edde\Api\Root\Profiler;

use Edde\Api\Root\Profiler\Endpoint\DisableEndpoint;
use Edde\Api\Root\Profiler\Endpoint\EnableEndpoint;
use Edde\Api\Root\Profiler\Endpoint\IsEnabledEndpoint;
use Edde\Api\Root\Profiler\Endpoint\NamesEndpoint;
use Edde\Api\Root\Profiler\Endpoint\ProfilersEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class ProfilerRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			DisableEndpoint::class,
			EnableEndpoint::class,
			IsEnabledEndpoint::class,
			NamesEndpoint::class,
			ProfilersEndpoint::class,
		]);
	}
}
