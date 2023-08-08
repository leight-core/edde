<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;

class FetchGenerator extends AbstractGenerator {
	public function generate(): void {
		$schemaOutput = sprintf('%s/src/schema', $this->output);
		$exportOutput = sprintf('%s/src/$export', $this->output);
		@mkdir($schemaOutput, 0777, true);
		@mkdir($exportOutput, 0777, true);

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$meta = $this->rpcService->resolve($name)->getMeta();
			$requestMeta = $meta->getRequestMeta();
			$responseMeta = $meta->getResponseMeta();

			if (($name = $requestMeta->getSchema()) && $schema = $this->schemaLoader->load($name)) {
				$this->generateSchema($schema, $schemaExport, $schemaOutput, $exportOutput);
			}
			if (($name = $responseMeta->getSchema()) && $schema = $this->schemaLoader->load($name)) {
				$this->generateSchema($schema, $schemaExport, $schemaOutput, $exportOutput);
			}
		}
	}
}
