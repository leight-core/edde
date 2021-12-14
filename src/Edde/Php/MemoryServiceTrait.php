<?php
declare(strict_types=1);

namespace Edde\Php;

/**
 * This service could be used to monitor memory limits of PHP and throw an Exception when
 * the limit could be reached.
 *
 * It's useful to prevent hard crashes of PHP particularly in daemons/long running jobs.
 */
trait MemoryServiceTrait {
	/** @var MemoryService */
	protected $memoryService;

	/**
	 * @Inject
	 *
	 * @param MemoryService $memoryService
	 */
	public function setMemoryService(MemoryService $memoryService): void {
		$this->memoryService = $memoryService;
	}
}
