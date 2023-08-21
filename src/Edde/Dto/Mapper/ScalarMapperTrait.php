<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

trait ScalarMapperTrait {
	/**
	 * @var ScalarMapper
	 */
	protected $scalarMapper;

	/**
	 * @Inject
	 *
	 * @param ScalarMapper $scalarMapper
	 *
	 * @return void
	 */
	public function setScalarMapper(ScalarMapper $scalarMapper): void {
		$this->scalarMapper = $scalarMapper;
	}
}
