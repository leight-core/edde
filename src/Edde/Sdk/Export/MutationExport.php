<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class MutationExport extends AbstractRpcExport {
	public function export(): ?string {
		$import = [
            'import {withMutation} from "@pico/rpc";',
            'import {z}            from "@pico/utils";',
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

		$export[] = vsprintf('
export const with%s = withMutation({
	service: "%s",
	schema:  {
		request:  %s%s,
		response: %s%s,
	},%s
});
export type IWith%s = typeof with%s;
		', [
			$rpcName,
			$this->escapeHandlerName(get_class($this->handler)),
			$requestSchema,
			$requestMeta->isOptional() ? '.nullish()' : '',
			$responseSchema,
			$responseMeta->isOptional() ? '.nullish()' : '',
			$meta->hasInvalidators() ? sprintf('
	invalidator: async ({queryClient}) => Promise.any([
		%s
	]),', implode(
				"\n\t\t",
				array_map(
					function ($item) {
						return sprintf("queryClient.invalidateQueries({
			queryKey: [%s],
		}),", sprintf('"%s"', $this->escapeHandlerName($item)));
					},
					$meta->getInvalidators()
				)
			)) : '',
			$rpcName,
			$rpcName,
		]);

		return $this->toExport($export);
	}
}
