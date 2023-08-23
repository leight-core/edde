<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

class SourceTableExport extends AbstractRpcExport {
    public function export(): ?string {
        $rpcName = $this->handler->getName();

        $import = [
            'import {type FC} from "react";',
            sprintf('import {with%s} from "../rpc/with%s";', $rpcName, $rpcName),
        ];

        $export = [
            '"use client";',
            $this->toExport($import, "\n"),
        ];

        $export[] = vsprintf('
export interface I%sTableProps {
}
        
export const %sTable: FC<I%sTableProps> = props => {
};
		', [
            $rpcName,
            $rpcName,
            $rpcName,
        ]);

        return $this->toExport($export);
    }
}
