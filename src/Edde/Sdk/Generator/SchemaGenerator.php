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
			$type = $schemaExport->getSchemaName($schema);
			$schemaName = $type . 'Schema';
			file_put_contents(sprintf('%s/%s.ts', $schemaOutput, $schemaName), $export);

			file_put_contents(sprintf('%s/%s.ts', $exportOutput, $schemaName), sprintf('export {%s} from "../schema/%s";', $schemaName, $schemaName));
			file_put_contents(sprintf('%s/I%s.ts', $exportOutput, $schemaName), sprintf('export {type I%s} from "../schema/%s";', $schemaName, $schemaName));
			file_put_contents(sprintf('%s/I%s.ts', $exportOutput, $type), sprintf('export {type I%s} from "../schema/%s";', $type, $schemaName));

			file_put_contents(sprintf('%s/$export.ts', $exportOutput), implode("\n", [
				sprintf('export * from "./%s";', $schemaName),
				sprintf('export * from "./I%s";', $schemaName),
				sprintf('export * from "./I%s";', $type),
				"",
			]), FILE_APPEND);
		}
		foreach ($schema->getAttributes() as $attribute) {
			if ($attribute->hasSchema()) {
				$this->generateSchema($attribute->getSchema(), $schemaExport, $schemaOutput, $exportOutput);
			}
		}
	}

	public function generate(): ?string {
		$schemaExport = $this->container->injectOn(new SchemaExport());

		$schemaOutput = sprintf('%s/src/schema', $this->output);
		$exportOutput = sprintf('%s/src/$export', $this->output);
		@mkdir($schemaOutput, 0777, true);
		@mkdir($exportOutput, 0777, true);

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);

			if (($name = $handler->getRequestMeta()->getSchema()) && $schema = $this->schemaLoader->load($name)) {
				$this->generateSchema($schema, $schemaExport, $schemaOutput, $exportOutput);
			}
			if (($name = $handler->getResponseMeta()->getSchema()) && $schema = $this->schemaLoader->load($name)) {
				$this->generateSchema($schema, $schemaExport, $schemaOutput, $exportOutput);
			}
		}

		return null;
	}
}