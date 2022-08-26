<?php
declare(strict_types=1);

namespace Edde\Process;

trait ProcessServiceTrait {
	/** @var ProcessService */
	protected $processService;

	/**
	 * @Inject
	 *
	 * @param ProcessService $processService
	 */
	public function setProcessService(ProcessService $processService): void {
		$this->processService = $processService;
	}
}
