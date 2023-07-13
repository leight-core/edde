<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

/**
 * This is optional service used to collect all "online" (active) RPC handlers.
 *
 * Later on it can be used for example to generate client-side SDK.
 */
interface IRpcHandlerIndex {
	/**
	 * @return string[]
	 */
	function getHandlers(): array;

	/**
	 * Register RPC handler into an index; this name  is used as a service name for RPC calls.
	 *
	 * @param string $rpcHandlerClass
	 */
	function register(string $rpcHandlerClass): void;

	/**
	 * Register all handlers at once (overriding existing ones). To add an individual handler, use register().
	 *
	 * @param array $handlerClasses
	 */
	function indexOf(array $handlerClasses): void;
}
