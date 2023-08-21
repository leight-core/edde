<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

trait InputTypeMapperTrait {
	/**
	 * @var InputTypeMapper
	 */
	protected $inputTypeMapper;

	/**
	 * @Inject
	 *
	 * @param InputTypeMapper $inputTypeMapper
	 *
	 * @return void
	 */
	public function setInputTypeMapper(InputTypeMapper $inputTypeMapper): void {
		$this->inputTypeMapper = $inputTypeMapper;
	}
}
