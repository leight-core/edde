<?php
declare(strict_types=1);

namespace Edde\Api\Shared\File;

use Edde\Api\Shared\File\Endpoint\CommitEndpoint;
use Edde\Api\Shared\File\Endpoint\DownloadEndpoint;
use Edde\Api\Shared\File\Endpoint\FilesEndpoint;
use Edde\Api\Shared\File\Endpoint\GcEndpoint;
use Edde\Api\Shared\File\Endpoint\RefreshEndpoint;
use Edde\Api\Shared\File\Endpoint\StaleEndpoint;
use Edde\Api\Shared\File\Endpoint\UploadEndpoint;
use Edde\Http\AbstractRouterGroup;
use Slim\Interfaces\RouteCollectorProxyInterface;

class FileRouterGroup extends AbstractRouterGroup {
	public function register(RouteCollectorProxyInterface $routeCollectorProxy) {
		$this->endpoints($routeCollectorProxy, [
			CommitEndpoint::class,
			DownloadEndpoint::class,
			FilesEndpoint::class,
			GcEndpoint::class,
			RefreshEndpoint::class,
			StaleEndpoint::class,
			UploadEndpoint::class,
		]);
	}
}
