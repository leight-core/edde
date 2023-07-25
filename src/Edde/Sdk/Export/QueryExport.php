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
		$requestSchema = 'z.undefined()';
		$responseSchema = 'z.undefined()';

		if (($name = $this->handler->getRequestSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $requestSchema = $schemaExport->getSchemaName($schema) . 'Schema', $requestSchema);
		}
		if (($name = $this->handler->getResponseSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $responseSchema = $schemaExport->getSchemaName($schema) . 'Schema', $responseSchema);
		}

		$export = [
			$this->toExport($import, "\n"),
		];

		$export[] = vsprintf('
export const with%s = withQuery({
	service: "%s",
	schema:  {
		request:  %s%s,
		response: %s%s,
	},
});
		', [
			$rpcName,
			$this->escapeHandlerName(get_class($this->handler)),
			$requestSchema,
			$this->handler->isRequestSchemaOptional() ? '.nullish()' : '',
			$responseSchema,
			$this->handler->isResponseSchemaOptional() ? '.nullish()' : '',
		]);

		return $this->toExport($export);
	}
}
