<?php
declare(strict_types=1);

namespace Edde\Config;

trait ConfigServiceTrait {
	/** @var ConfigService */
	protected $configService;

	/**
	 * @Inject
	 *
	 * @param ConfigService $configService
	 */
	public function setConfigService(ConfigService $configService): void {
		$this->configService = $configService;
	}
}
