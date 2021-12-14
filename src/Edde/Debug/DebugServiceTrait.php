<?php
declare(strict_types=1);

namespace Edde\Debug;

trait DebugServiceTrait {
	/** @var DebugService */
	protected $debugService;

	/**
	 * @Inject
	 *
	 * @param DebugService $debugService
	 */
	public function setDebugService(DebugService $debugService): void {
		$this->debugService = $debugService;
	}
}
