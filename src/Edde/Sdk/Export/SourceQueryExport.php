<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Rpc\Service\RpcServiceTrait;

class SourceQueryExport extends AbstractRpcExport {
    use RpcServiceTrait;

    public function export(): ?string {
        if (!($countHandler = $this->handler->getMeta()->getMeta('withCountQuery'))) {
            return null;
        }
        $countHandler = $this->rpcService->resolve($countHandler);
        $countName = $countHandler->getName();

        $import = [
            'import {withSourceQuery} from "@pico/rpc";',
            'import {z} from "@pico/utils";',
            'import {createQueryStore} from "@pico/query";',
            sprintf('import {with%s} from "./with%s";', $countName, $countName),
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
export const with%s = withSourceQuery({
	service: "%s",
	withCountQuery: with%s,
	schema:  {
		filter: %s,
        orderBy: %s,
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

export const %sProvider = with%s.query.Provider;
		', [
            $rpcName,
            $this->escapeHandlerName(get_class($this->handler)),
            $countName,
            $filterSchema,
            $orderBySchema,
            $responseType,
            $this->handler->getName(),
            $filterSchema,
            $orderBySchema,
            $rpcName,
            $rpcName,
            $rpcName,
            $rpcName,
        ]);

        return $this->toExport($export);
    }
}
