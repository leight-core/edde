<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class QueryExport extends AbstractRpcExport {
	public function export(): ?string {
		$import = [
			'import {withMutation} from "@leight/rpc-client";',
			'import {z}			   from "@leight/utils";',
		];

		$rpcName = $this->handler->getName();
		$schemaExport = new SchemaExport();
		$requestSchema = 'z.any()';
		$responseSchema = 'z.any()';

		if (($name = $this->handler->getRequestSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $requestSchema = $schemaExport->getSchemaName($schema) . 'Schema', $requestSchema);
		}
		if (($name = $this->handler->getResponseSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $responseSchema = $schemaExport->getSchemaName($schema) . 'Schema', $responseSchema);
		}

		$export = [
			$this->toExport($import, "\n"),
		];

		$export[] = sprintf('
export const with%s = withQuery({
	service: "%s",
	schema:  {
		request:  %s,
		response: %s,
	},
});
		', $rpcName, $this->escapeHandlerName(get_class($this->handler)), $requestSchema, $responseSchema);

		return $this->toExport($export);
	}
}
