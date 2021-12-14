<?php
declare(strict_types=1);

namespace Edde\Progress;

use Edde\Log\LoggerTrait;
use Edde\Php\Exception\MemoryLimitException;
use Edde\Php\MemoryServiceTrait;
use Throwable;

/**
 * Abstract progress does basically nothing except of one important thing:
 * it automagically monitors amount of memory being used in PHP against it's
 * limit (using MemoryService) and throwing an Exception if the limit is reached.
 */
abstract class AbstractProgress implements IProgress {
	use LoggerTrait;
	use MemoryServiceTrait;

	/** @var int */
	protected $total = 0;
	/** @var int */
	protected $success = 0;
	/** @var int */
	protected $error = 0;

	protected function progress(): float {
		return (100 * ($this->success + $this->error)) / max($this->total, 1);
	}

	/**
	 * @throws MemoryLimitException
	 */
	public function check(): void {
		try {
			$this->memoryService->logThreshold(['tags' => ['job']]);
			$this->memoryService->check(80);
		} catch (MemoryLimitException $exception) {
			throw $exception;
		} catch (Throwable $exception) {
			/**
			 * Do nothing else as job check should not kill the job (progress) itself in the cost of
			 * potentially malfunctioning memory check.
			 */
			$this->logger->error($exception);
		}
	}
}
