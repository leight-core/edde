<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

trait OutputTypeMapperTrait {
	/**
	 * @var OutputTypeMapper
	 */
	protected $outputTypeMapper;

	/**
	 * @Inject
	 *
	 * @param OutputTypeMapper $outputTypeMapper
	 *
	 * @return void
	 */
	public function setOutputTypeMapper(OutputTypeMapper $outputTypeMapper): void {
		$this->outputTypeMapper = $outputTypeMapper;
	}
}
