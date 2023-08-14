<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\SourceQueryExport;

class SourceQueryGenerator extends AbstractGenerator {
	public function generate(): void {
		$sourceQueryExport = $this->container->injectOn(new SourceQueryExport());

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);
			$meta = $handler->getMeta();
			if (!$meta->isQuery()) {
				continue;
			}
			$export = $sourceQueryExport
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
