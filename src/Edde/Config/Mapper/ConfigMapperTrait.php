<?php
declare(strict_types=1);

namespace Edde\Config\Mapper;

trait ConfigMapperTrait {
	/** @var ConfigMapper */
	protected $configMapper;

	/**
	 * @Inject
	 *
	 * @param ConfigMapper $configMapper
	 */
	public function setConfigMapper(ConfigMapper $configMapper): void {
		$this->configMapper = $configMapper;
	}
}
