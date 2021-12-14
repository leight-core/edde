<?php
declare(strict_types=1);

namespace Edde\Config\Repository;

trait ConfigRepositoryTrait {
	/** @var ConfigRepository */
	protected $configRepository;

	/**
	 * @Inject
	 *
	 * @param ConfigRepository $configRepository
	 */
	public function setConfigRepository(ConfigRepository $configRepository): void {
		$this->configRepository = $configRepository;
	}
}
