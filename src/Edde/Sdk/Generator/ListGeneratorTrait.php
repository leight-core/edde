<?php
declare(strict_types=1);

namespace Edde\Sdk\Generator;

trait ListGeneratorTrait {
	/** @var ListGenerator */
	protected $listGenerator;

	/**
	 * @Inject
	 *
	 * @param ListGenerator $listGenerator
	 */
	public function setListGenerator(ListGenerator $listGenerator): void {
		$this->listGenerator = $listGenerator;
	}
}
