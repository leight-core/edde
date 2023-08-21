<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\QueryInputExport;

class QueryInputGenerator extends AbstractGenerator {
	public function generate(): void {
		$queryInputExport = $this->container->injectOn(new QueryInputExport());

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);
			$meta = $handler->getMeta();
			if (!$meta->isQuery()) {
				continue;
			}
			$export = $queryInputExport
				->withHandler($handler)
				->export();
			if (!$export) {
				continue;
			}

			$name = sprintf('%sInput', $handler->getName());
			$this->writeTo(
				sprintf('src/ui/%s.ts', $name),
				$export
			);
			$this->writeTo(
				sprintf('src/$export/%s.ts', $name),
				sprintf('export {%s} from "../ui/%s";', $name, $name)
			);
			$this->writeTo(
				'src/$export/$export.ts',
				implode("\n", [
					sprintf('export * from "./%s";', $name),
					"",
				]),
				FILE_APPEND
			);
		}
	}
}
