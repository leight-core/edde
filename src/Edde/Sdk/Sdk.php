<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Container\ContainerTrait;
use Edde\File\FileServiceTrait;
use Edde\Sdk\Generator\RpcHandlerGenerator;

class Sdk {
	use ContainerTrait;
	use FileServiceTrait;

	public function generate(?string $output = null) {
		printf("Output: [%s]\n", $output = $output ?? sprintf('%s/sdk', getcwd()));
//		try {
//			$this->fileService->remove($output);
//		} catch (Throwable $throwable) {
//			// swallow
//		} finally {
//		}
		@mkdir($output, 0777, true);
		$this->container->injectOn($generator = new RpcHandlerGenerator());
		$generator
			->withOutput($output)
			->generate();
	}
}
