<?php
declare(strict_types=1);

namespace Edde\Math;

trait RandomServiceTrait {
	/** @var RandomService */
	protected $randomService;

	/**
	 * @Inject
	 *
	 * @param RandomService $randomService
	 */
	public function setRandomService(RandomService $randomService): void {
		$this->randomService = $randomService;
	}
}
