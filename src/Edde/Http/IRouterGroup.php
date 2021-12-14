<?php
declare(strict_types=1);

namespace Edde\Http;

use Slim\Interfaces\RouteCollectorProxyInterface;

interface IRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy);
}
