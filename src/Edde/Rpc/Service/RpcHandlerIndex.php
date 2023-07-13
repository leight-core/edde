<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

class RpcHandlerIndex implements IRpcHandlerIndex {
	/**
	 * @var string[]
	 */
	protected $handlers = [];

	function register(string $rpcHandlerClass): void {
		$this->handlers[] = $rpcHandlerClass;
	}

	function indexOf(array $handlerClasses): void {
		$this->handlers = $handlerClasses;
	}
}
