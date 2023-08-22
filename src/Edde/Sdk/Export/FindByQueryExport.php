<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class FindByQueryExport extends AbstractRpcExport {
	public function export(): ?string {
		$import = [
			'import {withFindByQuery} from "@leight/rpc";',
			'import {z} from "@leight/utils";',
			'import {withQuerySchema, createQueryStore} from "@leight/query";',
		];

		$rpcName = $this->handler->getName();
		$schemaExport = new SchemaExport();
		$responseSchema = 'z.undefined().nullish()';
		$filterSchema = 'z.undefined().nullish()';
		$orderBySchema = 'z.undefined().nullish()';

		$meta = $this->handler->getMeta();
		$responseMeta = $meta->getResponseMeta();

		if (($name = $responseMeta->getSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $responseSchema = $schemaExport->getSchemaName($schema) . 'Schema', $responseSchema);
		}
		if (($name = $meta->getFilterSchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $filterSchema = $schemaExport->getSchemaName($schema) . 'Schema', $filterSchema);
		}
		if (($name = $meta->getOrderBySchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s} from "../schema/%s";', $orderBySchema = $schemaExport->getSchemaName($schema) . 'Schema', $orderBySchema);
		}

		$export = [
			'"use client";',
			$this->toExport($import, "\n"),
		];

		$responseType = sprintf('%s%s', $responseSchema, $responseMeta->isOptional() ? '.nullish()' : '');
		$export[] = vsprintf('
export const with%s = withFindByQuery({
	service: "%s",
	schema:  {
		request: withQuerySchema({
			filter: %s,
			orderBy: %s,
		}),
		response: %s,
	},
	query:   createQueryStore({
		name: "%s",
		schema: {
			filter: %s,
			orderBy: %s,
		},
	}),
});
export type IWith%s = typeof with%s;
		', [
			$rpcName,
			$this->escapeHandlerName(get_class($this->handler)),
			$filterSchema,
			$orderBySchema,
			$responseType,
			$this->handler->getName(),
			$filterSchema,
			$orderBySchema,
			$rpcName,
			$rpcName,
		]);

		return $this->toExport($export);
	}
}
