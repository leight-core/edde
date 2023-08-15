<?php
declare(strict_types=1);

namespace Edde\Date\Mapper;

use DI\Annotation\Inject;

trait IsoDateMapperTrait {
	/**
	 * @var IsoDateMapper
	 */
	protected $isoDateMapper;

	/**
	 * @Inject
	 *
	 * @param IsoDateMapper $isoDateMapper
	 *
	 * @return void
	 */
	public function setIsoDateMapper(IsoDateMapper $isoDateMapper): void {
		$this->isoDateMapper = $isoDateMapper;
	}
}
