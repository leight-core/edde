<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Container\ContainerTrait;
use Edde\File\FileServiceTrait;

class Sdk {
	use ContainerTrait;
	use FileServiceTrait;

	public function generate(?string $output = null) {
		printf("Output: [%s]\n", $output = $output ?? sprintf('%s/sdk', getcwd()));
		try {
			$this->fileService->remove($output);
		} finally {
			mkdir($output, 0777, true);
		}
		$this->container->injectOn($generator = new RpcHandlerGenerator());
		$generator
			->withOutput($output)
			->generate();
	}
}
