<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

trait FloatMapperTrait {
	/**
	 * @var FloatMapper
	 */
	protected $floatMapper;

	/**
	 * @Inject
	 *
	 * @param FloatMapper $floatMapper
	 *
	 * @return void
	 */
	public function setFloatMapper(FloatMapper $floatMapper): void {
		$this->floatMapper = $floatMapper;
	}
}
