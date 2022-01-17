<?php
declare(strict_types=1);

namespace Edde\Api\Shared\User;

use Edde\Api\Shared\User\Endpoint\LoginEndpoint;
use Edde\Api\Shared\User\Endpoint\LogoutEndpoint;
use Edde\Api\Shared\User\Endpoint\TicketEndpoint;
use Edde\Api\Shared\User\Endpoint\UpdateSettingsEndpoint;
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
