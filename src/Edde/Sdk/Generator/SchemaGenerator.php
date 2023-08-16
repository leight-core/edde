<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Schema\ISchema;
use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\SchemaExport;

class SchemaGenerator extends AbstractGenerator {
	protected function generateSchema(ISchema $schema, SchemaExport $schemaExport): void {
		if ($export = $schemaExport->withSchema($schema)->export()) {
			$type = $schemaExport->getSchemaName($schema);
			$schemaName = $type . 'Schema';
			$this->writeTo(sprintf('src/schema/%s.ts', $schemaName), $export);
			$this->writeTo(sprintf('src/$export/%s.ts', $schemaName), sprintf('export {%s} from "../schema/%s";', $schemaName, $schemaName));
			$this->writeTo(sprintf('src/$export/I%s.ts', $schemaName), sprintf('export {type I%s} from "../schema/%s";', $schemaName, $schemaName));
			$this->writeTo(sprintf('src/$export/I%s.ts', $type), sprintf('export {type I%s} from "../schema/%s";', $type, $schemaName));
			$this->writeTo(
				'src/$export/$export.ts',
				implode("\n", [
					sprintf('export * from "./%s";', $schemaName),
					sprintf('export * from "./I%s";', $schemaName),
					sprintf('export * from "./I%s";', $type),
					"",
				]),
				FILE_APPEND
			);
		}
		foreach ($schema->getAttributes() as $attribute) {
			if ($attribute->hasSchema()) {
				$this->generateSchema($attribute->getSchema(), $schemaExport);
			}
		}
	}

	public function generate(): void {
		$schemaExport = $this->container->injectOn(new SchemaExport());

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$meta = $this->rpcService->resolve($name)->getMeta();
			$requestMeta = $meta->getRequestMeta();
			$responseMeta = $meta->getResponseMeta();

			($name = $requestMeta->getSchema()) && ($schema = $this->schemaLoader->load($name)) && $this->generateSchema($schema, $schemaExport);
			($name = $responseMeta->getSchema()) && ($schema = $this->schemaLoader->load($name)) && $this->generateSchema($schema, $schemaExport);
			($name = $meta->getValuesSchema()) && ($schema = $this->schemaLoader->load($name)) && $this->generateSchema($schema, $schemaExport);
			($name = $meta->getFilterSchema()) && ($schema = $this->schemaLoader->load($name)) && $this->generateSchema($schema, $schemaExport);
			($name = $meta->getOrderBySchema()) && ($schema = $this->schemaLoader->load($name)) && $this->generateSchema($schema, $schemaExport);
		}
	}
}
