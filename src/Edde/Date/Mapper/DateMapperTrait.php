<?php
declare(strict_types=1);

namespace Edde\Date\Mapper;

trait DateMapperTrait {
	/** @var DateMapper */
	protected $dateMapper;

	/**
	 * @Inject
	 *
	 * @param DateMapper $dateMapper
	 */
	public function setDateMapper(DateMapper $dateMapper): void {
		$this->dateMapper = $dateMapper;
	}
}
