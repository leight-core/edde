<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class SourceQueryExport extends AbstractRpcExport {
	public function export(): ?string {
		$import = [
			'import {withSourceQuery} from "@leight/source";',
			'import {z} from "@leight/utils";',
		];

		$rpcName = $this->handler->getName();
		$schemaExport = new SchemaExport();
		$requestSchema = 'z.undefined().nullish()';
		$responseSchema = 'z.undefined().nullish()';

		$meta = $this->handler->getMeta();
		$requestMeta = $meta->getRequestMeta();
		$responseMeta = $meta->getResponseMeta();

		if (($name = $requestMeta->getSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $requestSchema = $schemaExport->getSchemaName($schema) . 'Schema', $requestSchema);
		}
		if (($name = $responseMeta->getSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $responseSchema = $schemaExport->getSchemaName($schema) . 'Schema', $responseSchema);
		}

		$export = [
			'"use client";',
			$this->toExport($import, "\n"),
		];

		$responseType = sprintf('%s%s', $responseSchema, $responseMeta->isOptional() ? '.nullish()' : '');
		$export[] = vsprintf('
export const with%s = withSourceQuery({
	service: "%s",
	schema:  withSourceQuery({
		filter:  %s%s,
		orderBy: %s,
	}),
});
		', [
			$rpcName,
			$this->escapeHandlerName(get_class($this->handler)),
			$requestSchema,
			$requestMeta->isOptional() ? '.nullish()' : '',
			$responseMeta->isArray() ? sprintf('z.array(%s)', $responseType) : $responseType,
		]);

		return $this->toExport($export);
	}
}
