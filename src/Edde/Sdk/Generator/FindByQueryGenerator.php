<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\FindByQueryExport;

class FindByQueryGenerator extends AbstractGenerator {
	public function generate(): void {
		$findByQueryExport = $this->container->injectOn(new FindByQueryExport());

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);
			$meta = $handler->getMeta();
			if (!$meta->isFindBy()) {
				continue;
			}
			$export = $findByQueryExport
				->withHandler($handler)
				->export();
			if (!$export) {
				continue;
			}

			$this->writeTo(
				sprintf('src/rpc/with%s.ts', $handler->getName()),
				$export
			);
			$this->writeTo(
				sprintf('src/$export/with%s.ts', $handler->getName()),
				sprintf('export {with%s} from "../rpc/with%s";', $handler->getName(), $handler->getName())
			);
			$this->writeTo(
				sprintf('src/$export/IWith%s.ts', $handler->getName()),
				sprintf('export {type IWith%s} from "../rpc/with%s";', $handler->getName(), $handler->getName())
			);
			$this->writeTo(
				'src/$export/$export.ts',
				implode("\n", [
					sprintf('export * from "./with%s";', $handler->getName()),
					sprintf('export * from "./IWith%s";', $handler->getName()),
					"",
				]),
				FILE_APPEND
			);
		}
	}
}
