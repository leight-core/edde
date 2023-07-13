<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Container\ContainerTrait;

class Sdk {
	use ContainerTrait;

	public function generate(?string $output = null) {
		$this->container->injectOn($generator = new RpcHandlerGenerator());
		$generator
			->withOutput($output ?? sprintf('%s/sdk', getcwd()))
			->generate();
	}
}
