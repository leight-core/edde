<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class MutationExport extends AbstractRpcExport {
	public function export(): ?string {
		$import = [
			'import {withMutation}  from "@leight/rpc-client";',
		];

		$rpcName = $this->handler->getName();
		$schemaExport = new SchemaExport();
		$requestSchema = 'undefined';
		$responseSchema = 'undefined';

		if (($name = $this->handler->getRequestSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $requestSchema = $schemaExport->getSchemaName($schema), $requestSchema);
		}
		if (($name = $this->handler->getResponseSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $responseSchema = $schemaExport->getSchemaName($schema), $responseSchema);
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
		', $rpcName, get_class($this->handler), $requestSchema, $responseSchema);

		return $this->toExport($export);
	}
}
