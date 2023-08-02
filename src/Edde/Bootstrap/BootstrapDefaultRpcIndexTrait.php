<?php
declare(strict_types=1);

namespace Edde\Bootstrap;

trait BootstrapDefaultRpcIndexTrait {
	/**
	 * @var BootstrapDefaultRpcIndex
	 */
	protected $bootstrapDefaultRpcIndex;

	/**
	 * @Inject
	 *
	 * @param BootstrapDefaultRpcIndex $bootstrapDefaultRpcIndex
	 *
	 * @return void
	 */
	public function setBootstrapDefaultRpcIndex(BootstrapDefaultRpcIndex $bootstrapDefaultRpcIndex): void {
		$this->bootstrapDefaultRpcIndex = $bootstrapDefaultRpcIndex;
	}
}
