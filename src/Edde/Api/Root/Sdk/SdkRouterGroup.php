<?php
declare(strict_types=1);

namespace Edde\Api\Root\Sdk;

use Edde\Api\Root\Sdk\Endpoint\DownloadEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class SdkRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			DownloadEndpoint::class,
		]);
	}
}
