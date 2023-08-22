<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

trait BoolMapperTrait {
	/**
	 * @var BoolMapper
	 */
	protected $boolMapper;

	/**
	 * @Inject
	 *
	 * @param BoolMapper $boolMapper
	 *
	 * @return void
	 */
	public function setBoolMapper(BoolMapper $boolMapper): void {
		$this->boolMapper = $boolMapper;
	}
}
