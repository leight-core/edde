<?php
declare(strict_types=1);

namespace Edde\Progress;

use Edde\Dto\SmartDto;
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
	public $total = 0;
	/** @var int */
	public $success = 0;
	/** @var int */
	public $error = 0;
	public $skip = 0;
	public $progress = 0;
	public $context;
	public $result;

	public function onStart(int $total = 1): void {
		$this->total = $total;
	}

	public function onCurrent($context = null): void {
		$this->context = $context;
	}

	public function onProgress(): void {
		$this->success++;
		$this->progress = $this->progress();
	}

	public function onSettled(SmartDto $response = null): void {
		$this->result = $response;
		$this->progress = $this->progress();
	}

	public function onError(Throwable $throwable, string $reference = null): void {
		$this->error++;
		$this->progress = $this->progress();
		$this->logger->error($throwable, ['context' => $this->context]);
	}

	public function onSkip(): void {
		$this->skip++;
		$this->progress = $this->progress();
	}

	public function onFailure(Throwable $throwable): void {
		$this->logger->error($throwable, ['context' => $this->context]);
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
			$this->logger->error($exception, ['context' => $this->context]);
		}
	}

	public function log(int $level, string $message, string $type = null, string $reference = null) {
		$this->logger->log($level, $message, [
			'context'   => $this->context,
			'type'      => $type,
			'reference' => $reference,
		]);
	}

	protected function progress(): float {
		return (100 * ($this->success + $this->error)) / max($this->total, 1);
	}
}
