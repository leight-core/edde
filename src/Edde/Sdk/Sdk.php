<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Container\ContainerTrait;
use Edde\File\FileServiceTrait;

class Sdk {
	use ContainerTrait;
	use FileServiceTrait;

	public function generate(?string $output = null) {
		printf("Output: [%s]\n", $output = realpath($output ?? sprintf('%s/sdk', getcwd())));
		$this->fileService->remove($output);
		$this->container->injectOn($generator = new RpcHandlerGenerator());
		$generator
			->withOutput($output)
			->generate();
	}
}
