<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Import;

use Edde\Api\Shared\Import\Endpoint\ExcelEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class ImportRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			ExcelEndpoint::class,
		]);
	}
}
