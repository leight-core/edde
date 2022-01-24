<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Job;

use Edde\Api\Shared\Job\Endpoint\CommitEndpoint;
use Edde\Api\Shared\Job\Endpoint\DeleteEndpoint;
use Edde\Api\Shared\Job\Endpoint\InterruptEndpoint;
use Edde\Api\Shared\Job\Endpoint\JobEndpoint;
use Edde\Api\Shared\Job\Endpoint\JobsEndpoint;
use Edde\Api\Shared\Job\Log\JobLogRouterGroup;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class JobRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			CommitEndpoint::class,
			DeleteEndpoint::class,
			InterruptEndpoint::class,
			JobEndpoint::class,
			JobsEndpoint::class,
		], [
			JobLogRouterGroup::class,
		]);
	}
}
