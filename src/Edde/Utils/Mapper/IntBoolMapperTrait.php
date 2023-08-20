<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

trait IntBoolMapperTrait {
	/**
	 * @var IntBoolMapper
	 */
	protected $intBoolMapper;

	/**
	 * @Inject
	 *
	 * @param IntBoolMapper $intBoolMapper
	 *
	 * @return void
	 */
	public function setIntBoolMapper(IntBoolMapper $intBoolMapper): void {
		$this->intBoolMapper = $intBoolMapper;
	}
}
