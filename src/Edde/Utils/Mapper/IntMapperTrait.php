<?php
declare(strict_types=1);

namespace Edde\Utils\Mapper;

trait IntMapperTrait {
	/**
	 * @var IntMapper
	 */
	protected $intMapper;

	/**
	 * @Inject
	 *
	 * @param IntMapper $intMapper
	 *
	 * @return void
	 */
	public function setIntMapper(IntMapper $intMapper): void {
		$this->intMapper = $intMapper;
	}
}
