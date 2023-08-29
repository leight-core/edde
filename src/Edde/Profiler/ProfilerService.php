<?php
declare(strict_types=1);

namespace Edde\Profiler;

use Edde\Config\ConfigServiceTrait;
use Edde\Dto\SmartServiceTrait;
use Edde\Profiler\Repository\ProfilerRepositoryTrait;
use Edde\Profiler\Schema\ProfilerSchema;
use Throwable;
use function filter_var;
use function microtime;
use const FILTER_VALIDATE_BOOLEAN;

class ProfilerService {
	use ConfigServiceTrait;
	use ProfilerRepositoryTrait;
	use SmartServiceTrait;

	protected $enabled;

	public function isEnabled(): bool {
		return $this->enabled ?? $this->enabled = (bool)filter_var($this->configService->get('profiler.enabled', false), FILTER_VALIDATE_BOOLEAN);
	}

	/**
	 * Measure the given callback; return it's return.
	 *
	 * @param string   $name
	 * @param callable $callback
	 *
	 * @return mixed
	 */
	public function profile(string $name, callable $callback) {
		$time = microtime(true);
		try {
			return $callback();
		} finally {
			try {
				$this->isEnabled() && $this->profilerRepository->create(
					$this->smartService->from(
						[
							'name'    => $name,
							'stamp'   => $stamp = microtime(true),
							'runtime' => $stamp - $time,
						],
						ProfilerSchema::class
					)
				);
			} catch (Throwable $throwable) {
				/**
				 * Swallow: profiler cannot kill the app nor spam logs with failures (as it can gather information
				 * through whole app).
				 */
			}
		}
	}

	public function enable() {
		$this->profilerRepository->truncate();
		$this->configService->update(['profiler.enabled' => true]);
	}

	public function disable() {
		$this->configService->update(['profiler.enabled' => false]);
	}
}
