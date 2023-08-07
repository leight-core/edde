<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class QueryExport extends AbstractRpcExport {
	public function export(): ?string {
		$import = [
			'import {withQuery} from "@leight/rpc";',
			'import {z}         from "@leight/utils";',
		];

		$rpcName = $this->handler->getName();
		$schemaExport = new SchemaExport();
		$requestSchema = 'z.undefined().nullish()';
		$responseSchema = 'z.undefined().nullish()';

		$requestMeta = $this->handler->getRequestMeta();
		$responseMeta = $this->handler->getResponseMeta();

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
export const with%s = withQuery({
	service: "%s",
	schema:  {
		request:  %s%s,
		response: %s,
	},
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
