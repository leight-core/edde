<?php
declare(strict_types=1);

namespace Edde\Profiler;

trait ProfilerServiceTrait {
	/** @var ProfilerService */
	protected $profilerService;

	/**
	 * @Inject
	 *
	 * @param ProfilerService $profilerService
	 */
	public function setProfilerService(ProfilerService $profilerService): void {
		$this->profilerService = $profilerService;
	}
}
