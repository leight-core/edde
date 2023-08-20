<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

trait BoolIntMapperTrait {
	/**
	 * @var BoolIntMapper
	 */
	protected $boolIntMapper;

	/**
	 * @Inject
	 *
	 * @param BoolIntMapper $boolIntMapper
	 *
	 * @return void
	 */
	public function setBoolIntMapper(BoolIntMapper $boolIntMapper): void {
		$this->boolIntMapper = $boolIntMapper;
	}
}
