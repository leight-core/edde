<?php
declare(strict_types=1);

namespace Edde\Profiler\Repository;

trait ProfilerRepositoryTrait {
	/** @var ProfilerRepository */
	protected $profilerRepository;

	/**
	 * @Inject
	 *
	 * @param ProfilerRepository $profilerRepository
	 */
	public function setProfilerRepository(ProfilerRepository $profilerRepository): void {
		$this->profilerRepository = $profilerRepository;
	}
}
