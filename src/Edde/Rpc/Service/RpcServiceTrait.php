<?php
declare(strict_types=1);

namespace Edde\Rpc\Service;

trait RpcServiceTrait {
	/**
	 * @var RpcService
	 */
	protected $rpcService;

	/**
	 * @Inject
	 *
	 * @param RpcService $rpcService
	 *
	 * @return void
	 */
	public function setRpcService(RpcService $rpcService): void {
		$this->rpcService = $rpcService;
	}
}
