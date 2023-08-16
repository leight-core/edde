<?php
declare(strict_types=1);

namespace Edde\Dto\Mapper;

trait SmartDtoMapperTrait {
	/**
	 * @var SmartDtoMapper
	 */
	protected $smartDtoMapper;

	/**
	 * @Inject
	 *
	 * @param SmartDtoMapper $smartDtoMapper
	 *
	 * @return void
	 */
	public function setSmartDtoMapper(SmartDtoMapper $smartDtoMapper): void {
		$this->smartDtoMapper = $smartDtoMapper;
	}
}
