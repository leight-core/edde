<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Rpc\Service\IRpcHandler;
use Edde\Schema\SchemaLoaderTrait;
use Edde\Sdk\AbstractExport;

class FormExport extends AbstractExport {
	use SchemaLoaderTrait;

	/**
	 * @var IRpcHandler
	 */
	protected $handler;

	public function withHandler(IRpcHandler $handler): self {
		$this->handler = $handler;
		return $this;
	}

	public function export(): ?string {
		$schemaExport = new SchemaExport();

		$rpcName = sprintf('with%s', $this->handler->getName());
		$meta = $this->handler->getMeta();
		$import = [
			'import {withRpcForm, type IFormSchema} from "@leight/form";',
			sprintf('import {%s} from "../rpc/%s";', $rpcName, $rpcName),
		];

		$valuesSchema = sprintf('%s.schema.request', $rpcName);

		if ($values = $meta->getValuesSchema()) {
			$schema = $this->schemaLoader->load($values);
			$import[] = sprintf('import {%s} from "../schema/%s";', $schemaName = $schemaExport->getSchemaName($schema) . 'Schema', $schemaName);
			$valuesSchema = $schemaName;
		}

		$export = [
			'"use client";',
			$this->toExport($import, "\n"),
			vsprintf(
				"export const %sFormContext = withRpcForm({
	schema: {
		ValuesSchema:    %s,
	},
	withMutation: %s,
	name:   %s.service,
});

export type I%sFormSchema = typeof %sFormContext[\"schema\"];
export type I%sFormContext = IFormSchema.RpcForm<I%sFormSchema>;
",
				[
					$this->handler->getName(),
					$valuesSchema,
					$rpcName,
					$rpcName,
					$this->handler->getName(),
					$this->handler->getName(),
					$this->handler->getName(),
					$this->handler->getName(),
				]
			),
		];
		return $this->toExport($export);
	}
}
