<?php
declare(strict_types=1);

namespace Edde\Utils;

use COM;
use Throwable;
use function count;
use function posix_kill;
use function strtolower;
use function substr;
use const PHP_OS;

class ProcessUtils {
	static public function isRunning($pid) {
		if (!$pid) {
			return null;
		}
		return strtolower(substr(PHP_OS, 0, 3)) == 'win' ? self::isRunningWin($pid) : self::isRunningUnix($pid);
	}

	static protected function isRunningWin($pid): ?bool {
		try {
			$wmi = new COM('winmgmts://');
			$processes = $wmi->ExecQuery('SELECT ProcessId FROM Win32_Process WHERE ProcessId = \'' . (int)$pid . '\'');
			return count($processes) > 0;
		} catch (Throwable $exception) {
			return null;
		}
	}

	static protected function isRunningUnix($pid) {
		try {
			return posix_kill($pid, 0);
		} catch (Throwable $exception) {
			return null;
		}
	}
}
