<?php
declare(strict_types=1);

namespace Edde\Profiler\Mapper;

trait ProfilerMapperTrait {
	/** @var ProfilerMapper */
	protected $profilerMapper;

	/**
	 * @Inject
	 *
	 * @param ProfilerMapper $profilerMapper
	 */
	public function setProfilerMapper(ProfilerMapper $profilerMapper): void {
		$this->profilerMapper = $profilerMapper;
	}
}
