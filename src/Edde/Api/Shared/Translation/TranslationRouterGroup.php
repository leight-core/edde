<?php
declare(strict_types=1);

namespace Edde\Api\Shared\Translation;

use Edde\Api\Shared\Translation\Endpoint\DropEndpoint;
use Edde\Api\Shared\Translation\Endpoint\TranslationsEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class TranslationRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			DropEndpoint::class,
			TranslationsEndpoint::class,
		]);
	}
}
