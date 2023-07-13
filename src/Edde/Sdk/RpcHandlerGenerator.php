<?php
declare(strict_types=1);

namespace Edde\Sdk;

use Edde\Rpc\Service\RpcHandlerIndexTrait;

class RpcHandlerGenerator extends AbstractGenerator {
	use RpcHandlerIndexTrait;

	protected $output;

	public function withOutput(string $output): RpcHandlerGenerator {
		$this->output = $output;
		return $this;
	}

	public function generate(): ?string {
		foreach ($this->rpcHandlerIndex->getHandlers() as $handler) {
			printf("Generating %s to %s\n", $handler, $this->output);
		}
		return $this->output;
	}
}
