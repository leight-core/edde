<?php
declare(strict_types=1);

namespace Edde\Api\Root\Upgrade\Endpoint;

use Edde\Debug\DebugServiceTrait;
use Edde\Phinx\UpgradeManagerTrait;
use Edde\Rest\Endpoint\AbstractEndpoint;
use Nyholm\Psr7\Stream;
use Throwable;
use function ob_get_clean;
use function ob_start;

class RunEndpoint extends AbstractEndpoint {
	use UpgradeManagerTrait;
	use DebugServiceTrait;

	public function get() {
		try {
			ob_start();
			$this->upgradeManager->upgrade();
			$response = ob_get_clean();
			return $this->response
				->withBody(Stream::create($response . "\nok\n"))
				->withHeader('Content-Type', 'text/plain')
				->withStatus(200);
		} catch (Throwable $throwable) {
			return $this->response
				->withBody(Stream::create($this->debugService->render($throwable)))
				->withHeader('Content-Type', 'text/html')
				->withStatus(500);
		}
	}
}
