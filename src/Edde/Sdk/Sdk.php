<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Container\ContainerTrait;
use Edde\File\FileServiceTrait;

class Sdk {
	use ContainerTrait;
	use FileServiceTrait;

	public function generate(?string $output = null) {
		$this->fileService->remove($output = $output ?? sprintf('%s/sdk', getcwd()));
		$this->container->injectOn($generator = new RpcHandlerGenerator());
		$generator
			->withOutput($output)
			->generate();
	}
}
