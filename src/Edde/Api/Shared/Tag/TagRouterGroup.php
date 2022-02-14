<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Tag;

use Edde\Api\Shared\Tag\Endpoint\TagsEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class TagRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			TagsEndpoint::class,
		]);
	}
}
