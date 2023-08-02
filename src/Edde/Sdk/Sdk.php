<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Container\ContainerTrait;
use Edde\File\FileServiceTrait;
use Edde\Sdk\Generator\PackageGenerator;

class Sdk {
	use ContainerTrait;
	use FileServiceTrait;

	public function generate(?string $output = null) {
		printf("Output: [%s]\n", $output = $output ?? sprintf('%s/client/packages/@edde/sdk', getcwd()));
		$this->fileService->remove($output);
		@mkdir($output, 0777, true);
		$this->container->injectOn($generator = new PackageGenerator());
		$generator
			->withOutput($output)
			->generate();
	}
}
