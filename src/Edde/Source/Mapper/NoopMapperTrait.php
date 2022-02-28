<?php
declare(strict_types=1);

namespace Edde\Source\Mapper;

trait NoopMapperTrait {
	/** @var NoopMapper */
	protected $noopMapper;

	/**
	 * @Inject
	 *
	 * @param NoopMapper $noopMapper
	 */
	public function setNoopMapper(NoopMapper $noopMapper): void {
		$this->noopMapper = $noopMapper;
	}
}
