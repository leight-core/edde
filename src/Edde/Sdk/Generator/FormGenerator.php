<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\FormExport;

class FormGenerator extends AbstractGenerator {
	public function generate(): void {
		$formExport = $this->container->injectOn(new FormExport());

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);
			$meta = $handler->getMeta();
			if (!$meta->isWithForm()) {
				continue;
			}
			$export = $formExport
				->withHandler($handler)
				->export();
			if (!$export) {
				continue;
			}

			$name = sprintf('%sForm', $handler->getName());
			$this->writeTo(
				sprintf('src/form/%s.ts', $name),
				$export
			);
			$this->writeTo(
				sprintf('src/$export/%s.ts', $name),
				sprintf('export {%s} from "../form/%s";', $name, $name)
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
