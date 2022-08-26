<?php
declare(strict_types=1);

namespace Edde\Process;

use COM;
use Edde\Log\LoggerTrait;
use Throwable;
use function count;
use function posix_kill;
use function strtolower;
use function substr;
use const PHP_OS;

class ProcessService {
	use LoggerTrait;

	public function isRunning($pid) {
		if (!$pid) {
			return null;
		}
		return strtolower(substr(PHP_OS, 0, 3)) == 'win' ? self::isRunningWin($pid) : self::isRunningUnix($pid);
	}

	protected function isRunningWin($pid): ?bool {
		try {
			$wmi = new COM('winmgmts://');
			$processes = $wmi->ExecQuery('SELECT ProcessId FROM Win32_Process WHERE ProcessId = \'' . (int)$pid . '\'');
			return count($processes) > 0;
		} catch (Throwable $exception) {
			$this->logger->error($exception);
			return null;
		}
	}

	protected function isRunningUnix($pid) {
		try {
			return posix_kill($pid, 0);
		} catch (Throwable $exception) {
			$this->logger->error($exception);
			return null;
		}
	}
}
