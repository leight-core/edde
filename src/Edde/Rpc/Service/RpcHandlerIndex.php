<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

class RpcHandlerIndex implements IRpcHandlerIndex {
	/**
	 * @var string[]
	 */
	protected $handlers = [];

	public function getHandlers(): array {
		return $this->handlers;
	}

	public function register(string $rpcHandlerClass): void {
		$this->handlers[] = $rpcHandlerClass;
	}

	public function indexOf(array $handlerClasses): void {
		$this->handlers = $handlerClasses;
	}

	public function using(array $handlerClasses): void {
		$this->handlers = array_merge($this->handlers, $handlerClasses);
	}
}
