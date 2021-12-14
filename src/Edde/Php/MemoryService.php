<?php
declare(strict_types=1);

namespace Edde\Php;

use ByteUnits\System;
use Edde\Log\LoggerTrait;
use Edde\Php\Exception\MemoryException;
use Edde\Php\Exception\MemoryLimitException;
use Edde\Utils\ArrayUtils;
use Exception;
use function ByteUnits\parse;

class MemoryService {
	use LoggerTrait;

	/**
	 * Get (parsed) memory limit from PHP.
	 *
	 * @return System
	 *
	 * @throws MemoryException
	 */
	public function getLimit(): System {
		try {
			/**
			 * B at the end is a hack as PHP config does not use it and thus parse throws an exception.
			 *
			 * This method ensures everything is OK.
			 */
			return parse(rtrim(ini_get('memory_limit'), 'B') . 'B');
		} catch (Exception $exception) {
			throw new MemoryException($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * Return current (parsed) memory usage
	 *
	 * @return System
	 *
	 * @throws MemoryException
	 */
	public function getUsage(): System {
		try {
			return parse(memory_get_usage() . 'B');
		} catch (Exception $exception) {
			throw new MemoryException($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * Return current (parsed) amount of available memory.
	 *
	 * @return System
	 *
	 * @throws MemoryException
	 */
	public function getFree(): System {
		return $this->getLimit()->remove($this->getUsage());
	}

	/**
	 * @return System
	 *
	 * @throws MemoryException
	 */
	public function getPeak(): System {
		try {
			return parse(memory_get_peak_usage() . 'B');
		} catch (Exception $exception) {
			throw new MemoryException($exception->getMessage(), 0, $exception);
		}
	}

	/**
	 * Returns percentage usage.
	 *
	 * @return float
	 *
	 * @throws MemoryException
	 */
	public function getThreshold(): float {
		return (100 * $this->getUsage()->numberOfBytes()) / $this->getLimit()->numberOfBytes();
	}

	/**
	 * @return float
	 *
	 * @throws MemoryException
	 */
	public function getPeakThreshold(): float {
		return (100 * $this->getPeak()->numberOfBytes()) / $this->getLimit()->numberOfBytes();
	}

	/**
	 * @param int $threshold percentual limit of the memory consumed.
	 *
	 * @throws MemoryException
	 * @throws MemoryLimitException
	 */
	public function check(int $threshold): void {
		if (($usage = $this->getThreshold()) > $threshold) {
			throw new MemoryLimitException(vsprintf('Memory limit [%.0f%% (%s)] threshold [%d%% (PHP limit %s)] reached.', [
				$usage,
				$this->getUsage()->format(),
				$threshold,
				$this->getLimit()->format(),
			]));
		}
	}

	/**
	 * Tells if a request had high memory usage peak (for example for critical logging or finding memory heavy endpoints).
	 *
	 * @param int $threshold
	 *
	 * @return bool
	 *
	 * @throws MemoryException
	 */
	public function isHighPeak(int $threshold = 80): bool {
		return $this->getPeakThreshold() > $threshold;
	}

	/**
	 * Do a debug log of memory usage.
	 *
	 * @param array $context
	 *
	 * @throws MemoryException
	 */
	public function log(array $context = []): void {
		$this->logger->debug(vsprintf('Memory usage: current [%s] of max [%s]; usage [~%.2f%%].', [
			$this->getUsage()->format(),
			$this->getLimit()->format(),
			$this->getThreshold(),
		]), ArrayUtils::mergeRecursive(['tags' => ['memory']], $context));
	}

	/**
	 * @param array $context
	 * @param int   $threshold
	 *
	 * @throws MemoryException
	 */
	public function logThreshold(array $context = [], int $threshold = 80) {
		if ($this->getThreshold() > $threshold) {
			$this->log($context);
		}
	}
}
