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
		file_put_contents("$output/package.json", json_encode([
			'version'         => '0.5.0',
			'name'            => '@edde/sdk',
			'description'     => 'Generated SDK',
			'sideEffects'     => false,
			'type'            => 'module',
			'main'            => 'src/index.ts',
			'module'          => 'src/index.ts',
			'types'           => 'src/index.ts',
			'dependencies'    => [
				'@leight/utils' => '^0.5.0',
			],
			'devDependencies' => [
				'@leight/tsconfig' => '^0.5.0',
				'typescript'       => '^5.1.3',
			],
		]));
		$this->container->injectOn($generator = new RpcHandlerGenerator());
		$generator
			->withOutput($output)
			->generate();
	}
}
