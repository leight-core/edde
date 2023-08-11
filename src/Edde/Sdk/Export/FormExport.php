<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Rpc\Service\IRpcHandler;
use Edde\Sdk\AbstractExport;

class FormExport extends AbstractExport {
	/**
	 * @var IRpcHandler
	 */
	protected $handler;

	public function withHandler(IRpcHandler $handler): self {
		$this->handler = $handler;
		return $this;
	}

	public function export(): ?string {
		$rpcName = sprintf('with%s', $this->handler->getName());
		$import = [
			'import {withRpcForm} from "@leight/form";',
			sprintf('import {%s} from "../rpc/%s";', $rpcName, $rpcName),
		];
		$export = [
			'"use client";',
			$this->toExport($import, "\n"),
			vsprintf(
				"export const %sFormContext = withRpcForm({
	schema: {
		ValueSchema:    %s.schema.request,
	},
	withMutation: %s,
	name:   %s.service,
});",
				[
					$this->handler->getName(),
					$rpcName,
					$rpcName,
					$rpcName,
				]
			),
		];
		return $this->toExport($export);
	}
}
