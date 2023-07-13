<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Container\ContainerTrait;
use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Rpc\Service\RpcServiceTrait;
use Edde\Sdk\AbstractGenerator;
use Edde\Sdk\Export\MutationExport;
use Edde\Sdk\Export\QueryExport;

class RpcHandlerGenerator extends AbstractGenerator {
	use RpcServiceTrait;
	use RpcHandlerIndexTrait;
	use ContainerTrait;

	public function generate(): ?string {
		$queryExport = $this->container->injectOn(new QueryExport());
		$mutationExport = $this->container->injectOn(new MutationExport());

		$rpcOutput = sprintf('%s/src/rpc', $this->output);
		$exportOutput = sprintf('%s/src/$export', $this->output);
		$this->mkdir($rpcOutput);
		$this->mkdir($exportOutput);

		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			$handler = $this->rpcService->resolve($name);

			$export = $handler->isMutator() ? $mutationExport->withHandler($handler)->export() : $queryExport->withHandler($handler)->export();
			$export && file_put_contents(sprintf('%s/with%s.ts', $rpcOutput, $handler->getName()), $export);

			file_put_contents(sprintf('%s/with%s.ts', $exportOutput, $handler->getName()), sprintf('export {with%s} from "../rpc/with%s";', $handler->getName(), $handler->getName()));

			file_put_contents(sprintf('%s/$export.ts', $exportOutput), implode("\n", [
				sprintf('export * from "./with%s";', $handler->getName()),
				"",
			]), FILE_APPEND);
		}
		return $this->output;
	}
}
