<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

use Edde\Rpc\Service\RpcHandlerIndexTrait;
use Edde\Rpc\Service\RpcServiceTrait;
use Edde\Sdk\AbstractGenerator;
use Throwable;

class RpcHandlerGenerator extends AbstractGenerator {
	use RpcServiceTrait;
	use RpcHandlerIndexTrait;

	protected $output;

	public function withOutput(string $output): RpcHandlerGenerator {
		$this->output = $output;
		return $this;
	}

	public function module(string $name): string {
		return sprintf('%s/%s', $this->output, str_replace('\\', '/', $name));
	}

	public function generate(): ?string {
		foreach ($this->rpcHandlerIndex->getHandlers() as $name) {
			try {
				printf("\tGenerating [%s] to [%s]\n", $name, $this->module($name));
//				$handler = $this->rpcService->resolve($name);

			} catch (Throwable $throwable) {
				// swallow
			}
		}
		return $this->output;
	}
}
