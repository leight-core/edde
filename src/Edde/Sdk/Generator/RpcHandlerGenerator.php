<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Container\ContainerTrait;
use Edde\Rpc\Service\IRpcHandler;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Rpc\Service\RpcServiceTrait;
use Edde\Sdk\AbstractGenerator;
use Throwable;

class RpcHandlerGenerator extends AbstractGenerator {
	use RpcServiceTrait;
	use RpcHandlerIndexTrait;
	use ContainerTrait;

	public function module(IRpcHandler $handler): string {
		return sprintf('%s/%s.ts', $this->output, $handler->getName());
	}

	public function generate(): ?string {
		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			try {
				$handler = $this->rpcService->resolve($name);

				printf("\tGenerating [%s] to [%s]\n", $name, $output = $this->module($handler));

				$export = [];

				file_put_contents(
					$output,
					implode(
						"\n",
						array_map(
							'trim',
							array_filter($export)
						)
					)
				);
			} catch (Throwable $throwable) {
				// swallow
			}
		}
		return $this->output;
	}
}
