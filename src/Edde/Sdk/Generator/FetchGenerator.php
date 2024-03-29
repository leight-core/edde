<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\FetchExport;

class FetchGenerator extends AbstractGenerator {
	public function generate(): void {
		$fetchExport = $this->container->injectOn(new FetchExport());

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);
			$meta = $handler->getMeta();
			if (!$meta->isFetch() || $meta->isQuery()) {
				continue;
			}
			$export = $fetchExport
				->withHandler($handler)
				->export();
			if (!$export) {
				continue;
			}

			$this->writeTo(
				sprintf('src/ui/%s.ts', $handler->getName()),
				$export
			);
			$this->writeTo(
				sprintf('src/$export/%s.ts', $handler->getName()),
				sprintf('export {%s} from "../ui/%s";', $handler->getName(), $handler->getName())
			);
			$this->writeTo(
				'src/$export/$export.ts',
				implode("\n", [
					sprintf('export * from "./%s";', $handler->getName()),
					"",
				]),
				FILE_APPEND
			);
		}
	}
}
