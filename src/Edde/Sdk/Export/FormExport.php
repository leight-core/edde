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
			'import {withForm} from "@leight/form";',
			sprintf('import {with%s} from "../rpc/with%s";', $rpcName, $rpcName),
		];
		$export = [
			'"use client";',
			$this->toExport($import, "\n"),
			vsprintf(
				"const %sFormContext = withForm({
	schema: {
		ValueSchema:    %s.schema.request,
		RequestSchema:  %s.schema.request,
		ResponseSchema: %s.schema.response,
	},
	name:   %s.service,
});",
				[
					$this->handler->getName(),
					$rpcName,
					$rpcName,
					$rpcName,
					$rpcName,
				]
			),
		];
		return $this->toExport($export);
	}
}
