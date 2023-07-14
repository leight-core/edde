<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class MutationExport extends AbstractRpcExport {
	public function export(): ?string {
		$import = [
			'import {withMutation} from "@leight/rpc";',
			'import {z}            from "@leight/utils";',
		];

		$rpcName = $this->handler->getName();
		$schemaExport = new SchemaExport();
		$requestSchema = 'z.never()';
		$responseSchema = 'z.never()';

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
export const with%s = withMutation({
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
