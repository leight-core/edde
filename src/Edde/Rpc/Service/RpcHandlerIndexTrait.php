<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

trait RpcHandlerIndexTrait {
	/**
	 * @var IRpcHandlerIndex
	 */
	protected $rpcHandlerIndex;

	/**
	 * @Inject
	 *
	 * @param IRpcHandlerIndex $rpcHandlerIndex
	 *
	 * @return void
	 */
	public function setRpcHandlerIndex(IRpcHandlerIndex $rpcHandlerIndex): void {
		$this->rpcHandlerIndex = $rpcHandlerIndex;
	}
}
