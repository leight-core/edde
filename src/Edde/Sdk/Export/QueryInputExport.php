<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Rpc\Service\IRpcHandler;
use Edde\Schema\SchemaLoaderTrait;
use Edde\Sdk\AbstractExport;

class QueryInputExport extends AbstractExport {
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
		$rpcName = sprintf('with%s', $this->handler->getName());
		$import = [
			'import {withSourceQueryInput}  from "@leight/form";',
			sprintf('import {%s} from "../rpc/%s";', $rpcName, $rpcName),
		];

		$export = [
			'"use client";',
			$this->toExport($import, "\n"),
			vsprintf(
				"export const %sInput = withSourceQueryInput({
    withSourceQuery: %s,
})",
				[
					$this->handler->getName(),
					$rpcName,
				]
			),
		];
		return $this->toExport($export);
	}
}
