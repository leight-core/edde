<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class SourceQueryExport extends AbstractRpcExport {
	public function export(): ?string {
		$import = [
			'import {withSourceQuery} from "@leight/source";',
			'import {withQuerySchema, createQueryStore} from "@leight/query";',
			'import {z} from "@leight/utils";',
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
			$import[] = sprintf('import {%s, type I%s} from "../schema/%s";', $filterSchema = $schemaExport->getSchemaName($schema) . 'Schema', $filterSchema, $filterSchema);
		}
		if (($name = $meta->getOrderBySchema()) && $schema = $this->schemaLoader->load($name)) {
			$import[] = sprintf('import {%s, type I%s} from "../schema/%s";', $orderBySchema = $schemaExport->getSchemaName($schema) . 'Schema', $orderBySchema, $orderBySchema);
		}

		$export = [
			'"use client";',
			$this->toExport($import, "\n"),
		];

		$responseType = sprintf('%s%s', $responseSchema, $responseMeta->isOptional() ? '.nullish()' : '');
		$export[] = vsprintf('
export const with%s = withSourceQuery({
	service: "%s",
	schema:  {
		request: withQuerySchema({
			filterSchema:  %s,
			orderBySchema: %s,
		}),
		response: %s,
		filter: %s,
		orderBy: %s,
	},
	query:   createQueryStore<I%s, I%s>({
		name: "%s",
	}),
});
		', [
			$rpcName,
			$this->escapeHandlerName(get_class($this->handler)),
			$filterSchema,
			$orderBySchema,
			$responseMeta->isArray() ? sprintf('z.array(%s)', $responseType) : $responseType,
			$filterSchema,
			$orderBySchema,
			$filterSchema,
			$orderBySchema,
			$this->handler->getName(),
		]);

		return $this->toExport($export);
	}
}
