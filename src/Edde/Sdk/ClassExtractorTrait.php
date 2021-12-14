<?php
declare(strict_types=1);

namespace Edde\Sdk;

trait ClassExtractorTrait {
	/** @var ClassExtractor */
	protected $classExtractor;

	/**
	 * @Inject
	 *
	 * @param ClassExtractor $classExtractor
	 */
	public function setClassExtractor(ClassExtractor $classExtractor): void {
		$this->classExtractor = $classExtractor;
	}
}
