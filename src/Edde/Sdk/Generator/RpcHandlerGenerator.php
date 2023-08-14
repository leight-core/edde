<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\MutationExport;
use Edde\Sdk\Export\QueryExport;

class RpcHandlerGenerator extends AbstractGenerator {
	public function generate(): void {
		$queryExport = $this->container->injectOn(new QueryExport());
		$mutationExport = $this->container->injectOn(new MutationExport());

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);
			$meta = $handler->getMeta();

			$this->writeTo(
				sprintf('src/rpc/with%s.ts', $handler->getName()),
				$meta->isMutator() ? $mutationExport->withHandler($handler)->export() : $queryExport->withHandler($handler)->export()
			);
			$this->writeTo(
				sprintf('src/$export/with%s.ts', $handler->getName()),
				sprintf('export {with%s} from "../rpc/with%s";', $handler->getName(), $handler->getName())
			);
			$this->writeTo(
				'src/$export/$export.ts',
				implode("\n", [
					sprintf('export * from "./with%s";', $handler->getName()),
					"",
				]),
				FILE_APPEND
			);
		}
	}
}
