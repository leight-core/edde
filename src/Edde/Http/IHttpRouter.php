<?php
declare(strict_types=1);

namespace Edde\Http;

use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface;

interface IHttpRouter {
	public function register(App $app);

	public function endpoints(RouteCollectorProxyInterface $routeCollectorProxy, array $endpoints, array $routerGroups = []): void;
}
