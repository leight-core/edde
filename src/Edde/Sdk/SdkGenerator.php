<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Container\ContainerTrait;

class SdkGenerator {
	use ContainerTrait;

	public function generate(string $output = './sdk') {
		$this->container->injectOn($generator = new RpcHandlerGenerator());
		$generator
			->withOutput($output)
			->generate();
	}
}
