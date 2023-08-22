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
				sprintf('src/rpc/with%sQuery.ts', $handler->getName()),
				$export
			);
			$this->writeTo(
				sprintf('src/$export/with%sQuery.ts', $handler->getName()),
				sprintf('export {with%sQuery} from "../rpc/with%sQuery";', $handler->getName(), $handler->getName())
			);
			$this->writeTo(
				sprintf('src/$export/IWith%sQuery.ts', $handler->getName()),
				sprintf('export {type IWith%sQuery} from "../rpc/with%sQuery";', $handler->getName(), $handler->getName())
			);
			$this->writeTo(
				'src/$export/$export.ts',
				implode("\n", [
					sprintf('export * from "./with%sQuery";', $handler->getName()),
					sprintf('export * from "./IWith%sQuery";', $handler->getName()),
					"",
				]),
				FILE_APPEND
			);
		}
	}
}
