<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class SourceTableExport extends AbstractRpcExport {
    public function export(): ?string {
        $rpcName = $this->handler->getName();

        $import = [
            'import {type ITableProps, Table} from "@pico/table";',
            sprintf('import {with%s, type IWith%s} from "../rpc/with%s";', $rpcName, $rpcName, $rpcName),
        ];

        $export = [
            '"use client";',
            $this->toExport($import, "\n"),
        ];

        // language=text
        $export[] = vsprintf('
export interface I%sTableProps<TColumns extends string> extends Omit<ITableProps<TColumns, IWith%s["schema"]["response"]>, "useQuery" | "schema"> {
}
        
export const %sTable<TColumns extends string>(props: I%sTableProps<TColumns>) => {
    return <Table
        useQuery={with%s.useQuery}
        schema={with%s.schema.response}
        {...props}
    />;
};
		', [
            $rpcName,
            $rpcName,
            $rpcName,
            $rpcName,
            $rpcName,
            $rpcName,
        ]);

        return $this->toExport($export);
    }
}
