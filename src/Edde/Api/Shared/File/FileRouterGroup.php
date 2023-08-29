<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File;

use Edde\Api\Shared\File\Endpoint\DownloadEndpoint;
use Edde\Api\Shared\File\Endpoint\UploadEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class FileRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			DownloadEndpoint::class,
			UploadEndpoint::class,
		]);
	}
}
