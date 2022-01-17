<?php
declare(strict_types=1);

namespace Edde\User\Api;

use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class UserRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			TicketEndpoint::class,
			LoginEndpoint::class,
			LogoutEndpoint::class,
			UpdateSettingsEndpoint::class,
		]);
	}
}
