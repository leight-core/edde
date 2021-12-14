<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait FetchGeneratorTrait {
	/** @var FetchGenerator */
	protected $fetchGenerator;

	/**
	 * @Inject
	 *
	 * @param FetchGenerator $fetchGenerator
	 */
	public function setFetchGenerator(FetchGenerator $fetchGenerator): void {
		$this->fetchGenerator = $fetchGenerator;
	}
}
