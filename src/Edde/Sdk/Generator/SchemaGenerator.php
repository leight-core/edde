<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Container\ContainerTrait;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Rpc\Service\RpcServiceTrait;
use Edde\Schema\ISchema;
use Edde\Schema\SchemaLoaderTrait;
use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\SchemaExport;

class SchemaGenerator extends AbstractGenerator {
	use RpcHandlerIndexTrait;
	use RpcServiceTrait;
	use ContainerTrait;
	use SchemaLoaderTrait;

	protected function generateSchema(ISchema $schema, SchemaExport $schemaExport, string $schemaOutput, string $exportOutput): void {
		$export = $schemaExport->withSchema($schema)->export();
		if ($export) {
			$schemaName = $schemaExport->getSchemaName($schema);
			file_put_contents(sprintf("%s/%s.ts", $schemaOutput, $schemaName), $export);
			file_put_contents(sprintf("%s/%s.ts", $export, $schemaName), <<<e
export \{$schemaName\} from "../schema/$schemaName.ts";
e
			);
			file_put_contents(sprintf("%s/\$export.ts", $export), sprintf('export * from "./%s"', $schemaName), FILE_APPEND);
		}
	}

	public function generate(): ?string {
		$schemaExport = $this->container->injectOn(new SchemaExport());

		$schemaOutput = sprintf("%s/src/schema", $this->output);
		$exportOutput = sprintf("%s/src/\$export", $this->output);
		@mkdir($schemaOutput);
		@mkdir($exportOutput);

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);

			if (($name = $handler->getRequestSchema()) && $schema = $this->schemaLoader->load($name)) {
				$this->generateSchema($schema, $schemaExport, $schemaOutput, $exportOutput);
			}
			if (($name = $handler->getResponseSchema()) && $schema = $this->schemaLoader->load($name)) {
				$this->generateSchema($schema, $schemaExport, $schemaOutput, $exportOutput);
			}
		}

		return null;
	}
}
