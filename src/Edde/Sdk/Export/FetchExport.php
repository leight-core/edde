<?php
declare(strict_types=1);

namespace Edde\Sdk\Export;

use Edde\Rpc\Service\IRpcHandler;
use Edde\Sdk\AbstractExport;

class FetchExport extends AbstractExport {
	/**
	 * @var IRpcHandler
	 */
	protected $handler;

	public function withHandler(IRpcHandler $handler): self {
		$this->handler = $handler;
		return $this;
	}

	public function export(): ?string {
		$import = [
			'import {withFetch} from "@leight/source";',
			sprintf('import {with%s} from "../rpc/with%s";', $this->handler->getName(), $this->handler->getName()),
		];
		$export = [
			'"use client";',
			$this->toExport($import, "\n"),
			vsprintf(
				"export const %s = withFetch({withQuery: with%s});",
				[
					$this->handler->getName(),
					$this->handler->getName(),
				]
			),
		];
		return $this->toExport($export);
	}
}
