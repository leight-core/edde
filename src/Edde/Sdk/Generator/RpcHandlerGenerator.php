<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Container\ContainerTrait;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Rpc\Service\RpcServiceTrait;
use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\SchemaExport;
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
				@mkdir($output, 0777, true);
				$handler = $this->rpcService->resolve($name);

				$export = [];

				$this->container->injectOn($schemaExport = new SchemaExport());

				$export[] = $schemaExport
					->withHandler($handler->getRequestSchema())
					->export();
				$export[] = $schemaExport
					->withHandler($handler->getResponseSchema())
					->export();

				file_put_contents(
					sprintf('%s/index.ts', $output),
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
