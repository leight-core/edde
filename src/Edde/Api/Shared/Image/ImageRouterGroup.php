<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Image;

use Edde\Api\Shared\Image\Endpoint\UpdateEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class ImageRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			UpdateEndpoint::class,
		]);
	}
}
