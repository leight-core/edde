<?php
declare(strict_types=1);

namespace Edde\Log;

/**
 * Service used to generate and trace id of a request (regardless of HTTP or CLI or whatever).
 *
 * This is useful for referencing events during request (for example in logs).
 */
trait TraceServiceTrait {
	/** @var TraceService */
	protected $traceService;

	/**
	 * @Inject
	 *
	 * @param TraceService $traceService
	 */
	public function setTraceService(TraceService $traceService): void {
		$this->traceService = $traceService;
	}
}
