<?php
declare(strict_types=1);

namespace Edde\Uuid\Mapper;

trait UuidMapperTrait {
	/**
	 * @var UuidMapper
	 */
	protected $uuidMapper;

	/**
	 * @Inject
	 *
	 * @param UuidMapper $uuidMapper
	 *
	 * @return void
	 */
	public function setUuidMapper(UuidMapper $uuidMapper): void {
		$this->uuidMapper = $uuidMapper;
	}
}
