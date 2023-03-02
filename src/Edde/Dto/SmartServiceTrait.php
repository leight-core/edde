<?php
declare(strict_types=1);

namespace Edde\Dto;

trait SmartServiceTrait {
	/** @var SmartService */
	protected $smartService;

	/**
	 * @Inject
	 *
	 * @param SmartService $smartService
	 */
	public function setSmartService(ISmartService $smartService): void {
		$this->smartService = $smartService;
	}
}
