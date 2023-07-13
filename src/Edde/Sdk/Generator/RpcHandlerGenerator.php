<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Container\ContainerTrait;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Rpc\Service\RpcServiceTrait;
use Edde\Sdk\AbstractGenerator;
use Throwable;

class RpcHandlerGenerator extends AbstractGenerator {
	use RpcServiceTrait;
	use RpcHandlerIndexTrait;
	use ContainerTrait;

	public function module(string $name): string {
		return sprintf('%s/%s', $this->output, str_replace('\\', '/', $name));
	}

	public function generate(): ?string {
		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			try {
				printf("\tGenerating [%s] to [%s]\n", $name, $output = $this->module($name));
				$handler = $this->rpcService->resolve($name);
				$this->container->injectOn($schemaGenerator = new SchemaGenerator());
				$schemaGenerator
					->withOutput(sprintf('%s/schema', $output))
					->withHandler($handler)
					->generate();
			} catch (Throwable $throwable) {
				// swallow
			}
		}
		return $this->output;
	}
}
