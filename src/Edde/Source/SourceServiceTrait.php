<?php
declare(strict_types=1);

namespace Edde\Source;

trait SourceServiceTrait {
	/**
	 * @var ISourceService
	 */
	protected $sourceService;

	/**
	 * @Inject
	 *
	 * @param ISourceService $sourceService
	 */
	public function setSourceService(ISourceService $sourceService): void {
		$this->sourceService = $sourceService;
	}
}
