<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\SourceQueryExport;
use Edde\Sdk\Export\SourceTableExport;

class SourceQueryGenerator extends AbstractGenerator {
    public function generate(): void {
        $sourceQueryExport = $this->container->injectOn(new SourceQueryExport());
        $sourceTableExport = $this->container->injectOn(new SourceTableExport());

        foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
            $handler = $this->rpcService->resolve($name);
            $meta = $handler->getMeta();
            if (!$meta->isQuery()) {
                continue;
            }
            $sourceQuery = $sourceQueryExport
                ->withHandler($handler)
                ->export();
            if (!$sourceQuery) {
                continue;
            }
            $sourceTable = $sourceTableExport
                ->withHandler($handler)
                ->export();
            if (!$sourceTable) {
                continue;
            }

            $this->writeTo(
                sprintf('src/rpc/with%s.ts', $handler->getName()),
                $sourceQuery
            );
            $this->writeTo(
                sprintf('src/$export/with%s.ts', $handler->getName()),
                sprintf('export {with%s} from "../rpc/with%s";', $handler->getName(), $handler->getName())
            );
            $this->writeTo(
                sprintf('src/$export/IWith%s.ts', $handler->getName()),
                sprintf('export {type IWith%s} from "../rpc/with%s";', $handler->getName(), $handler->getName())
            );

            $this->writeTo(
                sprintf('src/table/%sTable.tsx', $handler->getName()),
                $sourceTable
            );
            $this->writeTo(
                sprintf('src/$export/%sTable.ts', $handler->getName()),
                sprintf('export {%sTable} from "../table/%sTable";', $handler->getName(), $handler->getName())
            );
            $this->writeTo(
                sprintf('src/$export/I%sTableProps.ts', $handler->getName()),
                sprintf('export {type I%sTableProps} from "../table/%sTable";', $handler->getName(), $handler->getName())
            );

            $this->writeTo(
                'src/$export/$export.ts',
                implode("\n", [
                    sprintf('export * from "./with%s";', $handler->getName()),
                    sprintf('export * from "./IWith%s";', $handler->getName()),
                    sprintf('export * from "./%sTable";', $handler->getName()),
                    sprintf('export * from "./I%sTableProps";', $handler->getName()),
                    "",
                ]),
                FILE_APPEND
            );
        }
    }
}
