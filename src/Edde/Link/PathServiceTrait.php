<?php
declare(strict_types=1);

namespace Edde\Link;

trait PathServiceTrait {
	/** @var PathService */
	protected $pathService;

	/**
	 * @Inject
	 *
	 * @param PathService $pathService
	 */
	public function setPathService(PathService $pathService): void {
		$this->pathService = $pathService;
	}
}
