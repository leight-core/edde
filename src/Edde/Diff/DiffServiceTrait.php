<?php
declare(strict_types=1);

namespace Edde\Diff;

trait DiffServiceTrait {
	/** @var DiffService */
	protected $diffService;

	/**
	 * @Inject
	 *
	 * @param DiffService $diffService
	 */
	public function setDiffService(DiffService $diffService): void {
		$this->diffService = $diffService;
	}
}
